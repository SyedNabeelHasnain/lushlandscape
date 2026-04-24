<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class LocationPageController extends Controller
{
    public function hub(PageContextService $pageContext)
    {
        $cities = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')
            ->withCount(['inverseRelatedEntries as active_service_pages_count' => function ($q) {
                $q->where('relation_type', 'matrix_city')->where('status', 'published');
            }])
            ->orderBy('sort_order')
            ->get();

        $schema = SchemaService::breadcrumbList([['label' => 'Locations', 'url' => url('/locations')]]);
        $blocks = BlockBuilderService::getBlocks('locations_hub', 0);
        $context = $pageContext->listing('Locations', 'locations', url('/locations'), [
            'cities' => $cities,
        ]);

        return view('frontend.pages.locations-hub', compact('cities', 'schema', 'blocks', 'context'));
    }

    public function city(string $slug, PageContextService $pageContext)
    {
        $city = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('slug', $slug)->where('status', 'published')->firstOrFail();
        $servicePages = $city->inverseRelatedEntries()
            ->where('relation_type', 'matrix_city')
            ->where('status', 'published')
            ->with('relatedEntries') // to fetch the matrix_service
            ->orderBy('sort_order')
            ->get();

        $breadcrumbs = [
            ['label' => 'Locations', 'url' => url('/locations')],
            ['label' => $city->title],
        ];
        $schema = SchemaService::breadcrumbList($breadcrumbs).SchemaService::localBusiness($city->title);

        $blocks = BlockBuilderService::getBlocks('city', $city->id);
        $context = $pageContext->city($city, $servicePages);

        // All published cities for city-switcher widget
        $allCities = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')->orderBy('sort_order')->get();

        // Use the new dynamic entry fallback
        return view('frontend.entries.city', compact('city', 'servicePages', 'breadcrumbs', 'schema', 'blocks', 'context', 'allCities'));
    }
}
