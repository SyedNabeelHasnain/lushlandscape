<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PageBlock;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ServiceCityPageController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $query = ServiceCityPage::with(['service.category', 'city'])->orderBy('city_id')->orderBy('sort_order');

        if ($request->filled('city')) {
            $query->where('city_id', $request->city);
        }
        if ($request->filled('service')) {
            $query->where('service_id', $request->service);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $pages = $query->paginate(30);
        $cities = City::orderBy('sort_order')->pluck('name', 'id');
        $services = Service::orderBy('sort_order')->pluck('name', 'id');

        return View::make('admin.service-city-pages.index', compact('pages', 'cities', 'services'));
    }

    public function create()
    {
        $services = Service::with('category')->orderBy('sort_order')->get();
        $cities = City::orderBy('sort_order')->get();

        return View::make('admin.service-city-pages.form', compact('services', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'city_id' => 'required|exists:cities,id',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'hero_video_url' => 'nullable|url|max:500',
            'hero_image_2_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_3_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_4_media_id' => 'nullable|exists:media_assets,id',
            'page_title' => 'required|string|max:255',
            'h1' => 'required|string|max:255',
            'local_intro' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'is_indexable' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_indexable'] = $request->boolean('is_indexable');

        $service = Service::findOrFail($validated['service_id']);
        $city = City::findOrFail($validated['city_id']);
        $validated['system_slug'] = Str::slug($service->name).'-'.Str::slug($city->name);
        $validated['slug_final'] = $validated['system_slug'];
        $validated['navigation_label'] = $service->name;

        $serviceCityPage = ServiceCityPage::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Service-city page created.', [], route('admin.service-city-pages.edit', $serviceCityPage));
        }

        return Redirect::route('admin.service-city-pages.index')
            ->with('success', 'Service-city page created.');
    }

    public function edit(ServiceCityPage $serviceCityPage)
    {
        $serviceCityPage->load(['service.category', 'city', 'heroMedia', 'heroImage2', 'heroImage3', 'heroImage4']);
        $services = Service::with('category')->orderBy('sort_order')->get();
        $cities = City::orderBy('sort_order')->get();

        $pageType = 'service_city_page';
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, $serviceCityPage->id);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.service-city-pages.form', [
            'page' => $serviceCityPage,
            'services' => $services,
            'cities' => $cities,
            'blocks' => $blocks,
            'blockTypes' => $blockTypes,
        ]);
    }

    public function update(Request $request, ServiceCityPage $serviceCityPage)
    {
        $validated = $request->validate([
            'page_title' => 'required|string|max:255',
            'h1' => 'required|string|max:255',
            'custom_slug' => 'nullable|string|max:255',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'hero_video_url' => 'nullable|url|max:500',
            'hero_image_2_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_3_media_id' => 'nullable|exists:media_assets,id',
            'hero_image_4_media_id' => 'nullable|exists:media_assets,id',
            'local_intro' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'is_indexable' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_indexable'] = $request->boolean('is_indexable');

        // Build keywords JSON from comma-separated inputs
        $validated['keywords_json'] = [
            'primary' => array_filter(array_map('trim', explode(',', $request->input('keywords_primary', '')))),
            'secondary' => array_filter(array_map('trim', explode(',', $request->input('keywords_secondary', '')))),
            'long_tail' => array_filter(array_map('trim', explode(',', $request->input('keywords_long_tail', '')))),
        ];

        $serviceCityPage->update($validated);

        // Save unified blocks (Section manager + Block editor)
        $blocksData = json_decode($request->input('blocks_json', '[]'), true) ?: [];

        BlockBuilderService::saveUnifiedBlocks('service_city_page', $serviceCityPage->id, $blocksData);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Page updated.');
        }

        return Redirect::route('admin.service-city-pages.index')
            ->with('success', 'Service-city page updated.');
    }

    public function destroy(Request $request, ServiceCityPage $serviceCityPage)
    {
        PageBlock::forPage('service_city_page', $serviceCityPage->id)->delete();
        $serviceCityPage->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Page deleted.');
        }

        return Redirect::route('admin.service-city-pages.index')
            ->with('success', 'Service-city page deleted.');
    }

    public function matrix(Request $request)
    {
        $selectedCityId = $request->integer('city_id') ?: null;

        $categories = ServiceCategory::with(['services' => fn ($q) => $q->where('status', 'published')->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        $publishedServiceIds = $categories->flatMap(fn ($c) => $c->services)->pluck('id');
        $totalServices = $publishedServiceIds->count();

        // Cities with active/total counts (only counting published services)
        $cities = City::withCount([
            'servicePages as total_pages' => fn ($q) => $q->whereIn('service_id', $publishedServiceIds),
            'activeServicePages as active_pages' => fn ($q) => $q->whereIn('service_id', $publishedServiceIds),
        ])
            ->orderBy('sort_order')
            ->get();

        $selectedCity = $selectedCityId ? $cities->firstWhere('id', $selectedCityId) : null;

        // Pages for the selected city keyed by service_id
        $cityPages = $selectedCity
            ? ServiceCityPage::where('city_id', $selectedCity->id)
                ->select(['id', 'service_id', 'is_active', 'slug_final'])
                ->get()
                ->keyBy('service_id')
            : collect();

        return View::make('admin.service-city-pages.matrix',
            compact('cities', 'categories', 'selectedCity', 'cityPages', 'totalServices'));
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate(['city_id' => 'required|exists:cities,id']);

        $city = City::findOrFail($request->city_id);
        $activeIds = array_map('intval', $request->input('active_service_ids', []));
        $services = Service::where('status', 'published')->get();

        $existingPages = ServiceCityPage::where('city_id', $city->id)
            ->get()
            ->keyBy('service_id');

        $toInsert = [];
        $toUpdateActive = [];
        $toUpdateInactive = [];

        foreach ($services as $service) {
            $shouldBeActive = in_array($service->id, $activeIds);

            if ($existingPages->has($service->id)) {
                $page = $existingPages->get($service->id);
                if ($page->is_active !== $shouldBeActive) {
                    if ($shouldBeActive) {
                        $toUpdateActive[] = $page->id;
                    } else {
                        $toUpdateInactive[] = $page->id;
                    }
                }
            } else {
                $toInsert[] = [
                    'city_id' => $city->id,
                    'service_id' => $service->id,
                    'page_title' => $service->name.' in '.$city->name,
                    'h1' => $service->name.' in '.$city->name,
                    'navigation_label' => $service->name,
                    'is_active' => $shouldBeActive,
                    'is_indexable' => true,
                    'system_slug' => Str::slug($service->name).'-'.Str::slug($city->name),
                    'slug_final' => Str::slug($service->name).'-'.Str::slug($city->name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($toInsert, $toUpdateActive, $toUpdateInactive) {
            if (!empty($toInsert)) {
                ServiceCityPage::insert($toInsert);
            }

            if (!empty($toUpdateActive)) {
                ServiceCityPage::whereIn('id', $toUpdateActive)->update(['is_active' => true]);
            }

            if (!empty($toUpdateInactive)) {
                ServiceCityPage::whereIn('id', $toUpdateInactive)->update(['is_active' => false]);
            }
        });

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Service pages updated for '.$city->name.'.');
        }

        return Redirect::route('admin.service-city-matrix', ['city_id' => $city->id])
            ->with('success', 'Service pages updated for '.$city->name.'.');
    }

    public function toggle(Request $request, ServiceCityPage $serviceCityPage)
    {
        $serviceCityPage->update(['is_active' => ! $serviceCityPage->is_active]);

        if ($request->expectsJson()) {
            return response()->json(['is_active' => $serviceCityPage->is_active]);
        }

        return Redirect::back()->with('success', 'Page status toggled.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
        ]);

        $city = City::findOrFail($request->city_id);
        $services = Service::where('status', 'published')->get();
        
        $existingServiceIds = ServiceCityPage::where('city_id', $city->id)
            ->pluck('service_id')
            ->toArray();

        $toInsert = [];

        foreach ($services as $service) {
            if (! in_array($service->id, $existingServiceIds)) {
                $toInsert[] = [
                    'service_id' => $service->id,
                    'city_id' => $city->id,
                    'page_title' => $service->name.' in '.$city->name,
                    'h1' => $service->name.' in '.$city->name,
                    'navigation_label' => $service->name,
                    'is_active' => false,
                    'is_indexable' => true,
                    'system_slug' => Str::slug($service->name).'-'.Str::slug($city->name),
                    'slug_final' => Str::slug($service->name).'-'.Str::slug($city->name),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        $created = count($toInsert);
        if ($created > 0) {
            ServiceCityPage::insert($toInsert);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess("{$created} service-city pages generated for {$city->name}.");
        }

        return Redirect::back()->with('success', $created.' service-city pages generated for '.$city->name.'.');
    }
}
