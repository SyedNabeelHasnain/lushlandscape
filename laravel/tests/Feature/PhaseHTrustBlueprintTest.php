<?php

namespace Tests\Feature;

use App\Console\Services\ListingPageBlueprintService;
use App\Services\SingletonPageBuilderService;
use Tests\TestCase;

class PhaseHTrustBlueprintTest extends TestCase
{
    public function test_phase_h_contact_mapping()
    {
        // Force memory driver so it bypasses sqlite fulltext errors
        // \Illuminate\Support\Facades\Artisan::call('migrate');

        $service = new ListingPageBlueprintService(app(SingletonPageBuilderService::class));
        $blocks = collect($service->buildSingletonPage('contact'));

        $keys = $blocks->pluck('block_type')->toArray();

        $this->assertEquals([
            'faq_section',
        ], $keys);
    }

    public function test_phase_h_consultation_mapping()
    {
        $service = new ListingPageBlueprintService(app(SingletonPageBuilderService::class));
        $blocks = collect($service->buildSingletonPage('consultation'));

        $keys = $blocks->pluck('block_type')->toArray();

        $this->assertEquals([
            'process_steps',
        ], $keys);
    }

    public function test_phase_h_faq_index_mapping()
    {
        $service = new ListingPageBlueprintService(app(SingletonPageBuilderService::class));
        $blocks = collect($service->buildSingletonPage('faqs-index'));

        $keys = $blocks->pluck('block_type')->toArray();

        $this->assertEquals([
            'parallax_media_band',
            'faq_directory',
            'split_consultation_panel',
        ], $keys);
    }
}
