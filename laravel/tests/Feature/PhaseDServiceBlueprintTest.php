<?php

namespace Tests\Feature;

use App\Console\Services\ListingPageBlueprintService;
use App\Models\Service;
use App\Models\ServiceCategory;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhaseDServiceBlueprintTest extends TestCase
{
    public function test_phase_d_service_hub_mapping()
    {
        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildSingletonPage('services-hub'));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'service_categories',
            'editorial_split_feature',
            'process_steps',
            'split_consultation_panel'
        ], $keys);
        
        $this->assertEquals('contact-us', $blocks[4]['content']['form_slug']);
    }

    public function test_phase_d_service_category_mapping()
    {
        $category = new ServiceCategory([
            'name' => 'Interlocking',
            'slug_final' => 'interlocking'
        ]);

        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildServiceCategory($category));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'editorial_split_feature',
            'services_grid',
            'split_consultation_panel'
        ], $keys);
    }

    public function test_phase_d_service_detail_mapping()
    {
        $category = new ServiceCategory([
            'name' => 'Interlocking',
            'slug_final' => 'interlocking'
        ]);
        
        $svc = new Service([
            'category_id' => 1,
            'name' => 'Driveway Interlocking',
            'slug_final' => 'driveway-interlocking'
        ]);

        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildServiceDetail($svc));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'editorial_split_feature',
            'authority_grid',
            'services_grid',
            'split_consultation_panel'
        ], $keys);
    }
}