<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

trait HandlesAjaxRequests
{
    protected function isAjax(Request $request): bool
    {
        return $request->expectsJson() || $request->ajax();
    }

    // 200 success — optionally include a redirect URL for create operations
    protected function jsonSuccess(string $message, array $extra = [], ?string $redirect = null): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if ($redirect) {
            $payload['redirect'] = $redirect;
        }

        return response()->json($payload + $extra);
    }

    // 422 validation / 4xx error
    protected function jsonError(string $message, array $errors = [], int $status = 422): JsonResponse
    {
        $payload = ['success' => false, 'message' => $message];
        if ($errors) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $status);
    }
}
