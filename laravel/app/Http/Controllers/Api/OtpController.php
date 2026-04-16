<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function check(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $verified = OtpService::isVerified($request->email);

        return response()->json(['verified' => $verified]);
    }

    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $result = OtpService::send($request->email, $request->ip());

        $status = 200;
        if (! $result['success']) {
            $status = str_contains($result['message'] ?? '', 'wait') || str_contains($result['message'] ?? '', 'Too many') ? 429 : 400;
        }

        return response()->json($result, $status);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $result = OtpService::verify($request->email, $request->otp);

        return response()->json($result, $result['success'] ? 200 : 422);
    }
}
