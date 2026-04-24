<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\CustomerEmail;
use App\Models\EmailVerification;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public static function isVerified(string $email): bool
    {
        return EmailVerification::isEmailVerified($email);
    }

    public static function send(string $email, string $ipAddress): array
    {
        $otp = null;

        $result = DB::transaction(function () use ($email, $ipAddress, &$otp) {
            // Lock the row to prevent parallel requests bypassing the rate limit
            $recent = EmailVerification::where('email', $email)
                ->where('created_at', '>', now()->subSeconds(30))
                ->where('is_verified', false)
                ->lockForUpdate()
                ->first();

            if ($recent) {
                return ['success' => false, 'message' => 'Please wait 30 seconds before requesting a new code.'];
            }

            // Daily cap: max 50 OTP requests per email per 24 hours
            $dailyCount = EmailVerification::where('email', $email)
                ->where('created_at', '>', now()->subHours(24))
                ->count();

            if ($dailyCount >= 50) {
                return ['success' => false, 'message' => 'Too many verification requests today. Please try again tomorrow.'];
            }

            $otp = (string) random_int(100000, 999999);

            EmailVerification::create([
                'email' => $email,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(2),
                'ip_address' => $ipAddress,
            ]);

            return null;
        });

        if ($result !== null) {
            return $result;
        }

        try {
            $siteName = Setting::get('site_name', 'Super WMS');

            Mail::to($email)->send(new CustomerEmail(
                subject: "Your Verification Code - {$siteName}",
                greeting: 'Hello,',
                lines: ['You requested a verification code to confirm your email address. Please use the code below to complete your verification.'],
                highlightBlock: $otp,
                highlightLabel: 'Your Verification Code',
                highlightNote: 'This code expires in 2 minutes.',
                outroLines: ['If you did not request this code, you can safely ignore this email.'],
                preheader: "Your verification code is {$otp}",
            ));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP Mail Error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return ['success' => false, 'message' => 'Failed to send verification email.'];
        }

        return ['success' => true, 'message' => 'Verification code sent to your email.'];
    }

    public static function verify(string $email, string $otp): array
    {
        return DB::transaction(function () use ($email, $otp) {
            $record = EmailVerification::where('email', $email)
                ->where('is_verified', false)
                ->orderByDesc('created_at')
                ->lockForUpdate()
                ->first();

            if (! $record) {
                return ['success' => false, 'message' => 'No verification request found. Please request a new code.'];
            }

            if ($record->isExpired()) {
                return ['success' => false, 'message' => 'Code has expired. Please request a new one.'];
            }

            if ($record->attempts >= 5) {
                return ['success' => false, 'message' => 'Too many attempts. Please request a new code.'];
            }

            $record->increment('attempts');

            if ($record->otp !== $otp) {
                return ['success' => false, 'message' => 'Invalid code. Please check and try again.'];
            }

            $record->update([
                'is_verified' => true,
                'verified_at' => now(),
            ]);

            return ['success' => true, 'message' => 'Email verified successfully.'];
        });
    }
}
