<?php

namespace Tests\Feature;

use App\Console\Services\ListingPageBlueprintService;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class PhaseFPortfolioBlueprintTest extends TestCase
{
    public function test_phase_f_portfolio_index_mapping()
    {
        // Force memory driver so it bypasses sqlite fulltext errors
        // \Illuminate\Support\Facades\Artisan::call('migrate');
        
        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildSingletonPage('portfolio-index'));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'portfolio_gallery',
            'editorial_split_feature',
            'split_consultation_panel'
        ], $keys);
        
        $this->assertEquals('contact-us', $blocks[3]['content']['form_slug']);
    }

    public function test_phase_f_portfolio_category_mapping()
    {
        $category = new PortfolioCategory([
            'name' => 'Interlocking',
            'slug' => 'interlocking'
        ]);

        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildPortfolioCategory($category));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'portfolio_gallery',
            'split_consultation_panel'
        ], $keys);
        
        $this->assertEquals('contact-us', $blocks[2]['content']['form_slug']);
    }

    public function test_phase_f_portfolio_project_mapping()
    {
        $project = new PortfolioProject([
            'title' => 'Executive Estate Driveway',
            'slug' => 'executive-estate-driveway'
        ]);

        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildPortfolioProject($project));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'editorial_split_feature',
            'portfolio_gallery',
            'services_grid',
            'split_consultation_panel'
        ], $keys);
        
        $this->assertEquals('contact-us', $blocks[4]['content']['form_slug']);
    }
}
