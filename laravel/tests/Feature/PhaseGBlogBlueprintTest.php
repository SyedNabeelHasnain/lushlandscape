<?php

namespace Tests\Feature;

use App\Console\Services\ListingPageBlueprintService;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class PhaseGBlogBlueprintTest extends TestCase
{
    public function test_phase_g_blog_index_mapping()
    {
        // Force memory driver so it bypasses sqlite fulltext errors
        // \Illuminate\Support\Facades\Artisan::call('migrate');
        
        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildSingletonPage('blog-index'));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'blog_directory',
            'split_consultation_panel'
        ], $keys);
        
        $this->assertEquals('contact-us', $blocks[2]['content']['form_slug']);
    }

    public function test_phase_g_blog_category_mapping()
    {
        $category = new BlogCategory([
            'name' => 'Design Tips',
            'slug' => 'design-tips'
        ]);

        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildBlogCategory($category));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        $this->assertEquals([
            'parallax_media_band',
            'blog_directory',
            'split_consultation_panel'
        ], $keys);
    }

    public function test_phase_g_blog_post_mapping()
    {
        $post = new BlogPost([
            'title' => 'How to Choose Interlocking Stones',
            'slug' => 'how-to-choose-interlocking-stones'
        ]);

        $service = new ListingPageBlueprintService(app(\App\Services\SingletonPageBuilderService::class));
        $blocks = collect($service->buildBlogPost($post));
        
        $keys = $blocks->pluck('block_type')->toArray();
        
        // Blog post builder blocks (appended after the main article shell body)
        $this->assertEquals([
            'services_grid',
            'split_consultation_panel'
        ], $keys);
    }
}
