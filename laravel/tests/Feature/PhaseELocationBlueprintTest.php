<?php

namespace Tests\Feature;

use App\Console\Services\ListingPageBlueprintService;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use App\Services\SingletonPageBuilderService;
use Tests\TestCase;

class PhaseELocationBlueprintTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_phase_e_locations_hub_mapping()
    {
        $service = new ListingPageBlueprintService(app(SingletonPageBuilderService::class));
        $blocks = collect($service->buildSingletonPage('locations-hub'));

        $keys = $blocks->pluck('block_type')->toArray();

        $this->assertEquals([
            'parallax_media_band',
            'service_area_enclave',
            'editorial_split_feature',
            'split_consultation_panel',
        ], $keys);

        $this->assertEquals('tabbed-enclave', $blocks[1]['content']['presentation_mode']);
        $this->assertEquals('consultation', $blocks[3]['content']['form_slug']);
    }

    public function test_phase_e_city_mapping()
    {
        $city = new City([
            'name' => 'Toronto',
            'slug_final' => 'toronto',
        ]);

        $service = new ListingPageBlueprintService(app(SingletonPageBuilderService::class));
        $blocks = collect($service->buildCity($city));

        $keys = $blocks->pluck('block_type')->toArray();

        $this->assertEquals([
            'parallax_media_band',
            'editorial_split_feature',
            'services_grid',
            'split_consultation_panel',
        ], $keys);
    }

    public function test_phase_e_service_city_mapping()
    {
        $city = new City([
            'name' => 'Toronto',
            'slug_final' => 'toronto',
        ]);

        $svc = new Service([
            'name' => 'Driveway Interlocking',
            'slug_final' => 'driveway-interlocking',
        ]);

        $page = new ServiceCityPage([
            'city_id' => 1,
            'service_id' => 1,
        ]);
        // Set relations manually to mock
        $page->setRelation('city', $city);
        $page->setRelation('service', $svc);

        $service = new ListingPageBlueprintService(app(SingletonPageBuilderService::class));
        $blocks = collect($service->buildServiceCity($page));

        $keys = $blocks->pluck('block_type')->toArray();

        $this->assertEquals([
            'parallax_media_band',
            'editorial_split_feature',
            'authority_grid',
            'services_grid',
            'split_consultation_panel',
        ], $keys);
    }
}
