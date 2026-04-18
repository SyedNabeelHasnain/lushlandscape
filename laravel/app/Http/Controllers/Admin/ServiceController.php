<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ServiceController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $query = Service::with('category', 'cities')->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $request->search).'%');
        }

        $services = $query->paginate(20);
        $categories = ServiceCategory::orderBy('sort_order')->pluck('name', 'id');

        return View::make('admin.services.index', compact('services', 'categories'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('sort_order')->pluck('name', 'id');
        $parents = Service::whereNull('parent_id')->orderBy('name')->pluck('name', 'id');
        $cities = City::where('status', 'published')->orderBy('name')->pluck('name', 'id');

        return View::make('admin.services.form', compact('categories', 'parents', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
            'parent_id' => 'nullable|exists:services,id',
            'navigation_label' => 'nullable|string|max:255',
            'service_code' => 'nullable|string|max:50',
            'service_summary' => 'nullable|string',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:500',
            'default_og_title' => 'nullable|string|max:255',
            'default_og_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'hero_video_url' => 'nullable|url|max:500',
            'hero_image_2_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_3_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_4_media_id' => 'nullable|exists:media_assets,id',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
            'city_ids' => 'nullable|array',
            'city_ids.*' => 'exists:cities,id',
        ]);

        $cityIds = $request->input('city_ids', []);
        $validated['service_body'] = $this->parseServiceBody($request);
        $validated['keywords_json'] = [
            'primary' => is_array($request->input('keywords_primary')) ? $request->input('keywords_primary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_primary', '')))),
            'secondary' => is_array($request->input('keywords_secondary')) ? $request->input('keywords_secondary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_secondary', '')))),
            'long_tail' => is_array($request->input('keywords_long_tail')) ? $request->input('keywords_long_tail') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_long_tail', '')))),
        ];

        $blocksJson = $request->input('blocks_json');

        $service = DB::transaction(function () use ($validated, $cityIds, $blocksJson) {
            $service = Service::create($validated);
            $service->cities()->sync($cityIds);

            // Save blocks if submitted
            if ($blocksJson) {
                $blocksData = json_decode($blocksJson, true) ?? [];
                BlockBuilderService::saveUnifiedBlocks('service', $service->id, $blocksData);
            }

            return $service;
        });

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Service created.', [], route('admin.services.edit', $service));
        }

        return Redirect::route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::orderBy('sort_order')->pluck('name', 'id');
        $parents = Service::whereNull('parent_id')
            ->where('id', '!=', $service->id)
            ->orderBy('name')->pluck('name', 'id');
        $cities = City::where('status', 'published')->orderBy('name')->pluck('name', 'id');

        $service->load('category', 'heroMedia', 'heroImage2', 'heroImage3', 'heroImage4', 'cities', 'cityPages.city');

        $pageType = 'service';
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, $service->id);
        $blockTypes = BlockBuilderService::allTypes();

        return view('admin.services.form', compact('service', 'categories', 'parents', 'cities', 'blocks', 'blockTypes'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
            'parent_id' => 'nullable|exists:services,id',
            'navigation_label' => 'nullable|string|max:255',
            'custom_slug' => 'nullable|string|max:255',
            'service_code' => 'nullable|string|max:50',
            'service_summary' => 'nullable|string',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:500',
            'default_og_title' => 'nullable|string|max:255',
            'default_og_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:100',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'hero_video_url' => 'nullable|url|max:500',
            'hero_image_2_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_3_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_4_media_id' => 'nullable|exists:media_assets,id',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
            'city_ids' => 'nullable|array',
            'city_ids.*' => 'exists:cities,id',
        ]);

        $cityIds = $request->input('city_ids', []);
        $validated['service_body'] = $this->parseServiceBody($request);
        $validated['keywords_json'] = [
            'primary' => is_array($request->input('keywords_primary')) ? $request->input('keywords_primary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_primary', '')))),
            'secondary' => is_array($request->input('keywords_secondary')) ? $request->input('keywords_secondary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_secondary', '')))),
            'long_tail' => is_array($request->input('keywords_long_tail')) ? $request->input('keywords_long_tail') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_long_tail', '')))),
        ];

        $blocksJson = $request->input('blocks_json', '[]');

        DB::transaction(function () use ($service, $validated, $cityIds, $blocksJson) {
            $service->update($validated);
            $service->cities()->sync($cityIds);

            // Save unified blocks (Section manager + Block editor)
            $blocksData = json_decode($blocksJson, true) ?: [];
            BlockBuilderService::saveUnifiedBlocks('service', $service->id, $blocksData);
        });

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Service updated.');
        }

        return Redirect::route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Request $request, Service $service)
    {
        if ($service->cityPages()->count() > 0) {
            if ($this->isAjax($request)) {
                return $this->jsonError('Cannot delete service with city page assignments. Deactivate them first.');
            }

            return Redirect::back()->with('error', 'Cannot delete service with city page assignments. Deactivate them first.');
        }

        $service->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Service deleted.');
        }

        return Redirect::route('admin.services.index')
            ->with('success', 'Service deleted.');
    }

    private function parseServiceBody(Request $request): array
    {
        $body = $request->input('service_body', []);

        // Convert newline-separated strings to arrays
        foreach (['benefits', 'materials'] as $field) {
            if (! empty($body[$field])) {
                $body[$field] = array_values(array_filter(
                    array_map('trim', explode("\n", $body[$field]))
                ));
            } else {
                $body[$field] = [];
            }
        }

        return $body;
    }
}
