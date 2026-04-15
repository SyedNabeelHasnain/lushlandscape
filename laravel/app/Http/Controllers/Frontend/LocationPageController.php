<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class LocationPageController extends Controller
{
    public function hub(PageContextService $pageContext)
    {
        $cities = City::where('status', 'published')
            ->withCount('activeServicePages')
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
        $city = City::where('slug_final', $slug)->where('status', 'published')->with('heroMedia')->firstOrFail();
        $servicePages = $city->activeServicePages()->with('service.category')->orderBy('sort_order')->get();

        $breadcrumbs = [
            ['label' => 'Locations', 'url' => url('/locations')],
            ['label' => $city->name],
        ];
        $schema = SchemaService::breadcrumbList($breadcrumbs).SchemaService::localBusiness($city->name);
        
        // Unified blocks retrieval now includes legacy sections in order
        $blocks = BlockBuilderService::getBlocks('city', $city->id);
        $context = $pageContext->city($city, $servicePages);

        // All published cities for city-switcher widget
        $allCities = City::where('status', 'published')->orderBy('sort_order')->get();

        return view('frontend.pages.city', compact('city', 'servicePages', 'breadcrumbs', 'schema', 'blocks', 'context', 'allCities'));
    }
}
