<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AdminNotification;
use App\Mail\CustomerEmail;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Setting;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FormSubmitController extends Controller
{
    public function submit(Request $request, string $slug)
    {
        $form = Form::where('slug', $slug)->where('status', 'active')->with('fields')->firstOrFail();

        $rules = [];
        foreach ($form->fields as $field) {
            if ($field->type === 'checkbox' && is_array($field->options) && count($field->options) > 1) {
                $rules[$field->name] = $field->is_required
                    ? ['required', 'array', 'min:1']
                    : ['nullable', 'array'];
                $rules[$field->name.'.*'] = ['string', 'max:255'];

                continue;
            }

            $fieldRules = $field->is_required ? ['required'] : ['nullable'];

            match ($field->type) {
                'email' => array_push($fieldRules, 'email', 'max:255'),
                'tel' => array_push($fieldRules, 'string', 'max:30'),
                'url' => array_push($fieldRules, 'url', 'max:500'),
                'number' => array_push($fieldRules, 'numeric'),
                'textarea' => array_push($fieldRules, 'string', 'max:5000'),
                'select', 'radio' => array_push($fieldRules, 'string', 'max:255'),
                'checkbox' => array_push($fieldRules, 'accepted'),
                default => array_push($fieldRules, 'string', 'max:500'),
            };

            $rules[$field->name] = $fieldRules;
        }

        $validated = $request->validate($rules);

        // Sanitize text inputs
        $validated = array_map(fn ($v) => is_string($v) ? strip_tags($v) : $v, $validated);

        if ($form->honeypot_enabled && $request->filled('website_url_hp')) {
            return response()->json(['success' => true, 'message' => $form->success_message]);
        }

        if ($form->requires_email_verification) {
            $emailField = $form->fields->firstWhere('type', 'email');
            if ($emailField) {
                $email = $validated[$emailField->name] ?? null;
                if ($email && ! OtpService::isVerified($email)) {
                    return response()->json(['success' => false, 'message' => 'Please verify your email address first.'], 422);
                }
            }
        }

        // Determine if email was actually verified for this submission
        $emailVerified = false;
        if ($form->requires_email_verification) {
            $emailField = $form->fields->firstWhere('type', 'email');
            if ($emailField && isset($validated[$emailField->name])) {
                $emailVerified = OtpService::isVerified($validated[$emailField->name]);
            }
        }

        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'data' => $validated,
            'email_verified' => $emailVerified,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'utm_data' => [
                'source' => $request->input('utm_source'),
                'medium' => $request->input('utm_medium'),
                'campaign' => $request->input('utm_campaign'),
            ],
        ]);

        $siteName = Setting::get('site_name', 'Lush Landscape Service');
        $appUrl = rtrim(config('app.url'), '/');

        // Admin notification email
        if (! empty($form->email_to)) {
            try {
                $dataRows = [];
                foreach ($validated as $k => $v) {
                    $val = is_array($v) ? implode(', ', $v) : (string) $v;
                    $dataRows[] = ['label' => ucwords(str_replace('_', ' ', $k)), 'value' => strip_tags($val)];
                }

                $badge = $form->admin_badge ?: 'Form';

                $adminMail = new AdminNotification(
                    subject: "New {$form->name} Submission - {$siteName}",
                    title: "New {$form->name} Submission",
                    dataRows: $dataRows,
                    meta: ['Submitted' => now()->format('M d, Y \a\t h:i A'), 'IP Address' => $request->ip(), 'Submission ID' => "#{$submission->id}"],
                    subtitle: 'A new submission has been received from your website.',
                    badge: $badge,
                    actionText: 'View in Admin',
                    actionUrl: "{$appUrl}/admin/submissions/{$submission->id}",
                    preheader: "New {$form->name} submission received",
                );

                $mailer = Mail::to($form->email_to);
                if ($form->email_cc) {
                    $mailer->cc($form->email_cc);
                }
                if ($form->email_bcc) {
                    $mailer->bcc($form->email_bcc);
                }
                $mailer->queue($adminMail);
            } catch (\Throwable $e) {
                Log::error('Form email delivery failed', [
                    'form_id' => $form->id,
                    'submission_id' => $submission->id,
                    'error' => $e->getMessage(),
                ]);

                return response()->json(['success' => false, 'message' => 'There was an error sending your message. Please try again later or contact us directly.'], 500);
            }
        }

        // Customer confirmation email
        $emailField = $form->fields->firstWhere('type', 'email');
        $userEmail = $emailField ? ($validated[$emailField->name] ?? null) : null;
        $nameField = $form->fields->firstWhere('name', 'name');
        $userName = $nameField ? ($validated[$nameField->name] ?? null) : null;
        if (! $nameField) {
            $nameField = $form->fields->firstWhere('name', 'full_name');
            $userName = $nameField ? ($validated[$nameField->name] ?? null) : null;
        }

        if ($userEmail) {
            try {
                $subject = $form->email_subject ?: "Thank You - {$siteName}";
                $lines = $form->confirmation_message ? explode("\n", $form->confirmation_message) : ["Thank you for reaching out to us. We've received your message and our team will get back to you as soon as possible."];

                $greeting = $userName ? "Hi {$userName}," : 'Hello,';

                Mail::to($userEmail)->queue(new CustomerEmail(
                    subject: $subject,
                    greeting: $greeting,
                    lines: $lines,
                    actionText: $form->slug === 'consultation' ? 'View Our Portfolio' : null,
                    actionUrl: $form->slug === 'consultation' ? "{$appUrl}/portfolio" : null,
                    outroLines: ['If you have any questions, feel free to reply to this email or call us directly.'],
                    preheader: $subject,
                ));
            } catch (\Throwable $e) {
                Log::error('Customer confirmation email failed', [
                    'form_id' => $form->id,
                    'email' => $userEmail,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => $form->success_message]);
    }
}
