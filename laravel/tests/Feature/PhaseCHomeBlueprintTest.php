<?php

namespace Tests\Feature;

use App\Console\Services\HomePageBlueprintService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseCHomeBlueprintTest extends TestCase
{
    public function test_phase_c_home_blueprint_generates_correct_premium_mapping()
    {
        // Don't use RefreshDatabase trait because Mockery is missing. Just do what the service does:
        $service = new HomePageBlueprintService;
        $blocks = collect($service->build());

        $keys = $blocks->pluck('block_type')->toArray();

        $this->assertEquals([
            'parallax_media_band',
            'services_grid',
            'editorial_split_feature',
            'portfolio_gallery',
            'authority_grid',
            'process_steps',
            'service_area_enclave',
            'split_consultation_panel',
        ], $keys);

        $this->assertEquals('premium-2x2', $blocks[1]['content']['variant']);
        $this->assertEquals('stacked', $blocks[2]['content']['feature_layout']);
        $this->assertEquals('rail', $blocks[3]['content']['layout']);
        $this->assertEquals('elevated', $blocks[4]['content']['card_skin']);
        $this->assertEquals('premium-stack', $blocks[5]['content']['variant']);
        $this->assertEquals('tabbed-enclave', $blocks[6]['content']['presentation_mode']);
        $this->assertEquals('consultation', $blocks[7]['content']['form_slug']);
    }
}
