<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Models\Setting;
use App\Services\BlockBuilderService;
use App\Services\BlockVariableService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BlockVariableServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');
    }

    public function test_editor_variable_groups_include_core_context_tokens(): void
    {
        $groups = collect(app(BlockVariableService::class)->editorVariableGroups());
        $tokens = $groups->flatMap(fn (array $group) => collect($group['variables'])->pluck('token'))->values()->all();

        $this->assertContains('setting.site_name', $tokens);
        $this->assertContains('setting.phone', $tokens);
        $this->assertContains('service.url', $tokens);
        $this->assertContains('service.category_name', $tokens);
        $this->assertContains('city.url', $tokens);
        $this->assertContains('category.url', $tokens);
        $this->assertContains('page.meta_title', $tokens);
        $this->assertContains('setting.footer_copyright_text', $tokens);
        $this->assertContains('post.url', $tokens);
        $this->assertContains('project.url', $tokens);
        $this->assertContains('item.url', $tokens);
        $this->assertContains('item.category_name', $tokens);
        $this->assertContains('date.year', $tokens);
    }

    public function test_block_builder_dynamic_parser_resolves_setting_alias_model_and_url_variables(): void
    {
        $this->prepareSettingsTable();

        Setting::set('site_phone', '+1 (905) 555-0199');
        Setting::set('site_name', 'Lush Landscape');

        $category = new ServiceCategory([
            'name' => 'Patios',
            'slug_final' => 'patios',
        ]);

        $service = new Service([
            'name' => 'Stone Patios',
            'slug_final' => 'stone-patios',
        ]);
        $service->setRelation('category', $category);

        $city = new City([
            'name' => 'Hamilton',
            'slug_final' => 'hamilton',
            'province_name' => 'Ontario',
        ]);

        $resolved = BlockBuilderService::parseDynamicString(
            'Call {setting.phone} for {service.name} in {city.name}. Visit {service.url}.',
            [
                'service' => $service,
                'city' => $city,
            ]
        );

        $this->assertStringContainsString('+1 (905) 555-0199', $resolved);
        $this->assertStringContainsString('Stone Patios', $resolved);
        $this->assertStringContainsString('Hamilton', $resolved);
        $this->assertStringContainsString(route('services.detail', ['categorySlug' => 'patios', 'slug' => 'stone-patios']), $resolved);
    }

    public function test_variable_service_resolves_page_item_and_project_aliases(): void
    {
        $page = new ServiceCityPage([
            'page_title' => 'Retaining Walls in Burlington',
            'slug_final' => 'retaining-walls-burlington',
            'local_intro' => 'Custom retaining wall planning and installation.',
        ]);

        $post = new BlogPost([
            'title' => 'Spring Hardscape Tips',
            'slug' => 'spring-hardscape-tips',
            'excerpt' => 'Prepare your yard for spring.',
        ]);

        $project = new PortfolioProject([
            'title' => 'Backyard Retreat',
            'slug' => 'backyard-retreat',
            'description' => 'A full backyard transformation.',
        ]);

        $resolved = app(BlockVariableService::class)->parseContent([
            'heading' => '{page.title}',
            'summary' => '{page.summary}',
            'post_url' => '{post.url}',
            'project_url' => '{project.url}',
            'item_label' => '{item.title}',
            'item_url' => '{item.url}',
            'year' => '{year}',
        ], [
            'page' => $page,
            'post' => $post,
            'project' => $project,
            'item' => $project,
        ]);

        $this->assertSame('Retaining Walls in Burlington', $resolved['heading']);
        $this->assertSame('Custom retaining wall planning and installation.', $resolved['summary']);
        $this->assertSame(route('blog.show', ['slug' => 'spring-hardscape-tips']), $resolved['post_url']);
        $this->assertSame(route('portfolio.show', ['slug' => 'backyard-retreat']), $resolved['project_url']);
        $this->assertSame('Backyard Retreat', $resolved['item_label']);
        $this->assertSame(route('portfolio.show', ['slug' => 'backyard-retreat']), $resolved['item_url']);
        $this->assertSame(now()->format('Y'), $resolved['year']);
    }

    public function test_variable_service_resolves_metadata_and_related_name_aliases(): void
    {
        $serviceCategory = new ServiceCategory([
            'name' => 'Retaining Walls',
        ]);

        $service = new Service([
            'name' => 'Segmental Retaining Walls',
            'default_meta_title' => 'Retaining Walls Service',
            'default_meta_description' => 'Engineered retaining wall construction.',
        ]);
        $service->setRelation('category', $serviceCategory);

        $projectCity = new City([
            'name' => 'Burlington',
        ]);

        $projectService = new Service([
            'name' => 'Landscape Design',
        ]);

        $project = new PortfolioProject([
            'title' => 'Tiered Backyard Build',
        ]);
        $project->setRelation('city', $projectCity);
        $project->setRelation('service', $projectService);

        $resolved = app(BlockVariableService::class)->parseContent([
            'service_category' => '{service.category_name}',
            'service_meta_title' => '{service.meta_title}',
            'service_meta_description' => '{service.meta_description}',
            'project_city_name' => '{project.city_name}',
            'project_service_name' => '{project.service_name}',
            'item_category_name' => '{item.category_name}',
        ], [
            'service' => $service,
            'project' => $project,
            'item' => array_merge($project->toArray(), ['category_name' => 'Featured Projects']),
        ]);

        $this->assertSame('Retaining Walls', $resolved['service_category']);
        $this->assertSame('Retaining Walls Service', $resolved['service_meta_title']);
        $this->assertSame('Engineered retaining wall construction.', $resolved['service_meta_description']);
        $this->assertSame('Burlington', $resolved['project_city_name']);
        $this->assertSame('Landscape Design', $resolved['project_service_name']);
        $this->assertSame('Featured Projects', $resolved['item_category_name']);
    }

    protected function tearDown(): void
    {
        Setting::flushCache();

        if (Schema::hasTable('settings')) {
            Schema::drop('settings');
        }

        parent::tearDown();
    }

    private function prepareSettingsTable(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::drop('settings');
        }

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->nullable();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->nullable();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Setting::flushCache();
    }
}
