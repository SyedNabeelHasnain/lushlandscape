<?php

namespace App\Http\Controllers\Admin;

use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Services\AiContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiContentController extends Controller
{
    use HandlesAjaxRequests;

    public function generate(Request $request): JsonResponse
    {
        $request->validate([
            'field_context' => 'required|string|max:500',
            'current_value' => 'nullable|string|max:10000',
            'custom_instructions' => 'nullable|string|max:1000',
            'page_context' => 'nullable|string|max:2000',
        ]);

        if (! AiContentService::isAvailable()) {
            return $this->jsonError('AI content generation is not enabled or API key is missing.', [], 400);
        }

        $result = AiContentService::generate(
            $request->input('field_context'),
            $request->input('current_value'),
            $request->input('custom_instructions'),
            $request->input('page_context')
        );

        if (! $result['success']) {
            return $this->jsonError($result['error'] ?? 'Content generation failed.');
        }

        return $this->jsonSuccess('Content generated.', ['content' => $result['content']]);
    }
}
