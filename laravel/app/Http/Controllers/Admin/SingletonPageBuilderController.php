<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Services\BlockBuilderService;
use App\Services\SingletonPageBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class SingletonPageBuilderController extends Controller
{
    use HandlesAjaxRequests;

    public function __construct(
        private readonly SingletonPageBuilderService $registry
    ) {}

    public function edit(string $page)
    {
        $config = $this->resolvePage($page);
        $pageType = $config['page_type'];
        $pageId = $config['page_id'];
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, $pageId);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.page-builders.edit', compact('config', 'pageType', 'pageId', 'blocks', 'blockTypes'));
    }

    public function update(Request $request, string $page)
    {
        $config = $this->resolvePage($page);
        $blocksData = json_decode($request->input('blocks_json', '[]'), true) ?: [];

        BlockBuilderService::saveUnifiedBlocks($config['page_type'], $config['page_id'], $blocksData);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess($config['success_message']);
        }

        return Redirect::route('admin.page-builders.edit', ['page' => $config['key']])
            ->with('success', $config['success_message']);
    }

    private function resolvePage(string $page): array
    {
        return $this->registry->get($page);
    }
}
