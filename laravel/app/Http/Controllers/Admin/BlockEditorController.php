<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class BlockEditorController extends Controller
{
    use Concerns\HandlesAjaxRequests;

    public function edit(string $pageType, ?int $pageId = null)
    {
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, $pageId);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.blocks.edit', compact('pageType', 'pageId', 'blocks', 'blockTypes'));
    }

    public function update(Request $request, string $pageType, ?int $pageId = null)
    {
        $blocksData = $request->input('blocks');

        if (! is_array($blocksData)) {
            $blocksJson = $request->input('blocks_json', '[]');
            $blocksData = json_decode($blocksJson, true) ?: [];
        }

        BlockBuilderService::saveUnifiedBlocks($pageType, $pageId, $blocksData);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Blocks saved successfully.');
        }

        return Redirect::back()->with('success', 'Blocks saved successfully.');
    }
}
