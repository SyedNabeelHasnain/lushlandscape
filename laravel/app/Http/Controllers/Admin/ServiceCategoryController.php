<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ServiceCategoryController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $categories = ServiceCategory::withCount('services')
            ->orderBy('sort_order')
            ->paginate(20);

        return View::make('admin.service-categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = ServiceCategory::whereNull('parent_id')->orderBy('sort_order')->pluck('name', 'id');

        return View::make('admin.service-categories.form', compact('parents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'navigation_label' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:service_categories,id',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
        ]);

        $serviceCategory = ServiceCategory::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Category created.', [], route('admin.service-categories.edit', $serviceCategory));
        }

        return Redirect::route('admin.service-categories.index')
            ->with('success', 'Service category created successfully.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        $parents = ServiceCategory::whereNull('parent_id')
            ->where('id', '!=', $serviceCategory->id)
            ->orderBy('sort_order')
            ->pluck('name', 'id');

        $blocks = BlockBuilderService::getUnifiedBlocks('service_category', $serviceCategory->id);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.service-categories.form', [
            'category' => $serviceCategory,
            'parents' => $parents,
            'blocks' => $blocks,
            'blockTypes' => $blockTypes,
        ]);
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'navigation_label' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:service_categories,id',
            'custom_slug' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
        ]);

        $serviceCategory->update($validated);

        $blocksJson = $request->input('blocks_json');
        if ($blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true) ?? [];
            BlockBuilderService::saveUnifiedBlocks('service_category', $serviceCategory->id, $blocksData);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Category updated.');
        }

        return Redirect::route('admin.service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    public function destroy(Request $request, ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->services()->count() > 0) {
            if ($this->isAjax($request)) {
                return $this->jsonError('Cannot delete category with assigned services.');
            }

            return Redirect::back()->with('error', 'Cannot delete category with assigned services.');
        }

        BlockBuilderService::deleteAllBlocksForPage('service_category', $serviceCategory->id);
        $serviceCategory->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Category deleted.');
        }

        return Redirect::route('admin.service-categories.index')
            ->with('success', 'Service category deleted.');
    }
}
