<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\PageBlock;
use App\Models\StaticPage;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class StaticPageController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $pages = StaticPage::orderBy('sort_order')->paginate(20);

        return View::make('admin.static-pages.index', compact('pages'));
    }

    public function create()
    {
        return View::make('admin.static-pages.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:static_pages,slug',
            'page_type' => 'nullable|string|max:50',
            'template' => 'nullable|string|max:50',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'is_indexable' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_indexable'] = $request->boolean('is_indexable');

        $staticPage = StaticPage::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Static page created.', [], route('admin.static-pages.edit', $staticPage));
        }

        return Redirect::route('admin.static-pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit(StaticPage $staticPage)
    {
        $staticPage->load('heroMedia');
        $pageType = 'static_page';
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, $staticPage->id);
        $blockTypes = BlockBuilderService::allTypes();

        $sections = []; // Static pages don't have regions/sections but we pass it anyway for consistency

        return View::make('admin.static-pages.form', [
            'page' => $staticPage,
            'blocks' => $blocks,
            'blockTypes' => $blockTypes,
            'sections' => $sections,
        ]);
    }

    public function update(Request $request, StaticPage $staticPage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:static_pages,slug,'.$staticPage->id,
            'page_type' => 'nullable|string|max:50',
            'template' => 'nullable|string|max:50',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'is_indexable' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_indexable'] = $request->boolean('is_indexable');

        $staticPage->update($validated);

        // Save unified blocks
        $blocksJson = $request->input('blocks_json');
        if ($blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true) ?: [];
            BlockBuilderService::saveUnifiedBlocks('static_page', $staticPage->id, $blocksData);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Page updated.');
        }

        return Redirect::route('admin.static-pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Request $request, StaticPage $staticPage)
    {
        PageBlock::forPage('static_page', $staticPage->id)->delete();
        $staticPage->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Page deleted.');
        }

        return Redirect::route('admin.static-pages.index')->with('success', 'Page deleted.');
    }
}
