<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class CityController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $cities = City::withCount(['servicePages', 'activeServicePages'])
            ->orderBy('sort_order')
            ->paginate(20);

        return View::make('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return View::make('admin.cities.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'navigation_label' => 'nullable|string|max:255',
            'province_name' => 'nullable|string|max:255',
            'region_name' => 'nullable|string|max:255',
            'city_summary' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'hero_video_url' => 'nullable|url|max:500',
            'hero_image_2_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_3_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_4_media_id' => 'nullable|exists:media_assets,id',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:500',
            'default_og_title' => 'nullable|string|max:255',
            'default_og_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['province_name'] = $validated['province_name'] ?? 'Ontario';

        // Parse city_body JSON fields from form
        $validated['city_body'] = $this->parseCityBody($request);
        $validated['keywords_json'] = [
            'primary' => is_array($request->input('keywords_primary')) ? $request->input('keywords_primary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_primary', '')))),
            'secondary' => is_array($request->input('keywords_secondary')) ? $request->input('keywords_secondary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_secondary', '')))),
            'long_tail' => is_array($request->input('keywords_long_tail')) ? $request->input('keywords_long_tail') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_long_tail', '')))),
        ];

        $blocksJson = $request->input('blocks_json');

        $city = DB::transaction(function () use ($validated, $blocksJson) {
            $city = City::create($validated);

            // Save blocks if submitted
            if ($blocksJson) {
                $blocksData = json_decode($blocksJson, true) ?? [];
                BlockBuilderService::saveUnifiedBlocks('city', $city->id, $blocksData);
            }

            return $city;
        });

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('City created successfully.', [], route('admin.cities.edit', $city));
        }

        return Redirect::route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function edit(City $city)
    {
        $city->load('heroMedia', 'heroImage2', 'heroImage3', 'heroImage4', 'serviceCategories', 'servicePages.service.category');

        $pageType = 'city';
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, $city->id);
        $blockTypes = BlockBuilderService::allTypes();

        $allCategories = ServiceCategory::where('status', 'published')
            ->withCount(['services as services_count' => fn ($q) => $q->where('status', 'published')])
            ->orderBy('sort_order')->get();
        $activeCategoryIds = $city->serviceCategories->pluck('id')->toArray();

        return View::make('admin.cities.form', compact('city', 'blocks', 'blockTypes', 'allCategories', 'activeCategoryIds'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'navigation_label' => 'nullable|string|max:255',
            'custom_slug' => 'nullable|string|max:255',
            'province_name' => 'nullable|string|max:255',
            'region_name' => 'nullable|string|max:255',
            'city_summary' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'hero_video_url' => 'nullable|url|max:500',
            'hero_image_2_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_3_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_4_media_id' => 'nullable|exists:media_assets,id',
            'default_meta_title' => 'nullable|string|max:255',
            'default_meta_description' => 'nullable|string|max:500',
            'default_og_title' => 'nullable|string|max:255',
            'default_og_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:service_categories,id',
        ]);

        $validated['city_body'] = $this->parseCityBody($request);
        $validated['keywords_json'] = [
            'primary' => is_array($request->input('keywords_primary')) ? $request->input('keywords_primary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_primary', '')))),
            'secondary' => is_array($request->input('keywords_secondary')) ? $request->input('keywords_secondary') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_secondary', '')))),
            'long_tail' => is_array($request->input('keywords_long_tail')) ? $request->input('keywords_long_tail') : array_filter(array_map('trim', explode(',', (string) $request->input('keywords_long_tail', '')))),
        ];

        // Extract category_ids before mass-assignment
        $categoryIds = $validated['category_ids'] ?? [];
        unset($validated['category_ids']);

        $blocksJson = $request->input('blocks_json', '[]');

        DB::transaction(function () use ($city, $validated, $categoryIds, $blocksJson) {
            $city->update($validated);

            // Save unified blocks (Section manager + Block editor)
            $blocksData = json_decode($blocksJson, true) ?: [];
            BlockBuilderService::saveUnifiedBlocks('city', $city->id, $blocksData);

            // Sync category-city pivot + auto-manage service pages
            $selectedCatIds = array_map('intval', $categoryIds);
            $city->serviceCategories()->sync($selectedCatIds);
            $this->syncCategoryServicePages($city, $selectedCatIds);
        });

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('City updated successfully.');
        }

        return Redirect::route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy(Request $request, City $city)
    {
        if ($city->servicePages()->count() > 0) {
            if ($this->isAjax($request)) {
                return $this->jsonError('Cannot delete city with service page assignments.');
            }

            return Redirect::back()->with('error', 'Cannot delete city with service page assignments.');
        }

        $city->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('City deleted.');
        }

        return Redirect::route('admin.cities.index')
            ->with('success', 'City deleted.');
    }

    // Auto-create/activate service pages for enabled categories; deactivate for removed ones
    private function syncCategoryServicePages(City $city, array $enabledCategoryIds): void
    {
        $allCategories = ServiceCategory::with(['services' => fn ($q) => $q->where('status', 'published')])
            ->whereHas('services', fn ($q) => $q->where('status', 'published'))
            ->get();

        $existingPages = ServiceCityPage::where('city_id', $city->id)
            ->get()
            ->keyBy('service_id');

        $toInsert = [];
        $toUpdateActive = [];
        $toUpdateInactive = [];

        foreach ($allCategories as $category) {
            $isEnabled = in_array($category->id, $enabledCategoryIds);

            foreach ($category->services as $service) {
                if ($existingPages->has($service->id)) {
                    $page = $existingPages->get($service->id);
                    if ($isEnabled && ! $page->is_active) {
                        $toUpdateActive[] = $page->id;
                    } elseif (! $isEnabled && $page->is_active) {
                        $toUpdateInactive[] = $page->id;
                    }
                } else {
                    if ($isEnabled) {
                        $toInsert[] = [
                            'city_id' => $city->id,
                            'service_id' => $service->id,
                            'page_title' => $service->name.' in '.$city->name,
                            'h1' => $service->name.' in '.$city->name,
                            'navigation_label' => $service->name,
                            'is_active' => true,
                            'is_indexable' => true,
                            'system_slug' => Str::slug($service->name).'-'.Str::slug($city->name),
                            'slug_final' => Str::slug($service->name).'-'.Str::slug($city->name),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        if (! empty($toInsert)) {
            ServiceCityPage::insert($toInsert);
        }

        if (! empty($toUpdateActive)) {
            ServiceCityPage::whereIn('id', $toUpdateActive)->update(['is_active' => true]);
        }

        if (! empty($toUpdateInactive)) {
            ServiceCityPage::whereIn('id', $toUpdateInactive)->update(['is_active' => false]);
        }
    }

    private function parseCityBody(Request $request): array
    {
        $body = $request->input('city_body', []);

        // Convert newline-separated neighborhoods to array
        if (! empty($body['neighborhoods_served'])) {
            $body['neighborhoods_served'] = array_values(array_filter(
                array_map('trim', explode("\n", $body['neighborhoods_served']))
            ));
        } else {
            $body['neighborhoods_served'] = [];
        }

        return $body;
    }
}
