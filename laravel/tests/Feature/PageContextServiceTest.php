<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\BlockVariableService;
use App\Services\PageContextService;
use Tests\TestCase;

class PageContextServiceTest extends TestCase
{
    public function test_listing_context_provides_page_level_variables(): void
    {
        $context = app(PageContextService::class)->listing('Blog', 'blog', url('/blog'));

        $resolved = app(BlockVariableService::class)->parseContent([
            'title' => '{page.title}',
            'url' => '{page.url}',
            'slug' => '{page.slug}',
        ], $context);

        $this->assertSame('Blog', $resolved['title']);
        $this->assertSame(url('/blog'), $resolved['url']);
        $this->assertSame('blog', $resolved['slug']);
    }

    public function test_entity_contexts_promote_primary_models_to_page_scope(): void
    {
        $serviceCategory = new ServiceCategory([
            'id' => 5,
            'name' => 'Driveways',
            'slug_final' => 'driveways',
        ]);

        $service = new Service([
            'id' => 7,
            'name' => 'Interlocking Driveways',
            'slug_final' => 'interlocking-driveways',
        ]);
        $service->setRelation('category', $serviceCategory);

        $blogCategory = new BlogCategory([
            'id' => 9,
            'name' => 'Hardscaping',
            'slug' => 'hardscaping',
        ]);

        $post = new BlogPost([
            'id' => 11,
            'title' => 'How to Plan a New Driveway',
            'slug' => 'plan-new-driveway',
        ]);
        $post->setRelation('category', $blogCategory);

        $city = new City([
            'id' => 14,
            'name' => 'Hamilton',
            'slug_final' => 'hamilton',
            'province_name' => 'Ontario',
            'city_summary' => 'A growing service area.',
        ]);

        $portfolioCategory = new PortfolioCategory([
            'id' => 18,
            'name' => 'Residential',
            'slug' => 'residential',
        ]);

        $project = new PortfolioProject([
            'id' => 22,
            'title' => 'Front Yard Refresh',
            'slug' => 'front-yard-refresh',
        ]);
        $project->setRelation('service', $service);
        $project->setRelation('city', $city);
        $project->setRelation('category', $portfolioCategory);

        $postContext = app(PageContextService::class)->blogPost($post);
        $projectContext = app(PageContextService::class)->portfolioProject($project);

        $resolvedPost = app(BlockVariableService::class)->parseContent([
            'page_title' => '{page.title}',
            'category_name' => '{category.name}',
        ], $postContext);

        $resolvedProject = app(BlockVariableService::class)->parseContent([
            'page_title' => '{page.title}',
            'service_name' => '{service.name}',
            'city_name' => '{city.name}',
            'category_name' => '{category.name}',
            'province' => '{province_name}',
        ], $projectContext);

        $this->assertSame('How to Plan a New Driveway', $resolvedPost['page_title']);
        $this->assertSame('Hardscaping', $resolvedPost['category_name']);
        $this->assertSame('Front Yard Refresh', $resolvedProject['page_title']);
        $this->assertSame('Interlocking Driveways', $resolvedProject['service_name']);
        $this->assertSame('Hamilton', $resolvedProject['city_name']);
        $this->assertSame('Residential', $resolvedProject['category_name']);
        $this->assertSame('Ontario', $resolvedProject['province']);
    }
}
