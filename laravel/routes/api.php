<?php

use App\Http\Controllers\Api\FormSubmitController;
use App\Http\Controllers\Api\OtpController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('otp/check', [OtpController::class, 'check']);
        Route::post('otp/send', [OtpController::class, 'send']);
    });
    Route::middleware('throttle:3,1')->group(function () {
        Route::post('otp/verify', [OtpController::class, 'verify']);
    });
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('forms/{slug}/submit', [FormSubmitController::class, 'submit']);
    });
});
