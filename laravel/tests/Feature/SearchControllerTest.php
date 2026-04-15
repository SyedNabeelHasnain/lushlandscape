<?php

namespace Tests\Feature;

use App\Http\Controllers\Frontend\SearchController;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SearchControllerTest extends TestCase
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

        $this->createSearchTables();
    }

    public function test_live_search_uses_canonical_service_detail_urls(): void
    {
        $category = ServiceCategory::create([
            'name' => 'Sports Turf',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        Service::create([
            'category_id' => $category->id,
            'name' => 'Turf',
            'service_summary' => 'Premium artificial turf installations.',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        $response = app(SearchController::class)->live(Request::create('/search/live', 'GET', [
            'q' => 'Turf',
        ]));

        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(route('services.detail', [
            'categorySlug' => 'sports-turf',
            'slug' => 'turf',
        ]), $payload['services'][0]['url']);
        $this->assertSame(2, $payload['total']);
    }

    public function test_live_search_returns_category_and_portfolio_groups_when_they_match(): void
    {
        $category = ServiceCategory::create([
            'name' => 'Turf Care',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        PortfolioProject::create([
            'title' => 'Turf Transformation',
            'slug' => 'turf-transformation',
            'description' => 'Backyard turf transformation project.',
            'status' => 'published',
        ]);

        $response = app(SearchController::class)->live(Request::create('/search/live', 'GET', [
            'q' => 'Turf',
        ]));

        $payload = $response->getData(true);

        $this->assertSame(route('services.category', ['slug' => $category->slug_final]), $payload['categories'][0]['url']);
        $this->assertSame(route('portfolio.show', ['slug' => 'turf-transformation']), $payload['portfolio'][0]['url']);
    }

    public function test_full_search_results_render_canonical_links_and_service_summary(): void
    {
        $category = ServiceCategory::create([
            'name' => 'Sports Turf',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        Service::create([
            'category_id' => $category->id,
            'name' => 'Turf',
            'service_summary' => 'Premium artificial turf installations.',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        PortfolioProject::create([
            'title' => 'Turf Project',
            'slug' => 'turf-project',
            'description' => 'Signature turf portfolio project.',
            'status' => 'published',
        ]);

        $view = app(SearchController::class)->results(Request::create('/search', 'GET', [
            'q' => 'Turf',
            'type' => 'all',
        ]));

        $html = $view->render();

        $this->assertStringContainsString('href="'.route('services.detail', [
            'categorySlug' => 'sports-turf',
            'slug' => 'turf',
        ]).'"', $html);
        $this->assertStringContainsString('Premium artificial turf installations.', $html);
        $this->assertStringContainsString('href="'.route('portfolio.show', ['slug' => 'turf-project']).'"', $html);
        $this->assertStringNotContainsString('href="/services/turf"', $html);
    }

    private function createSearchTables(): void
    {
        Schema::dropAllTables();

        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('system_slug')->nullable();
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->nullable();
            $table->string('navigation_label')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->unsignedBigInteger('hero_media_id')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('system_slug')->nullable();
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->nullable();
            $table->string('navigation_label')->nullable();
            $table->text('service_summary')->nullable();
            $table->unsignedBigInteger('hero_media_id')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('system_slug')->nullable();
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->nullable();
            $table->string('region_name')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->unsignedBigInteger('featured_image_id')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('question');
            $table->longText('answer');
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('hero_media_id')->nullable();
            $table->string('status')->default('draft');
            $table->date('completion_date')->nullable();
            $table->timestamps();
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('internal_title')->nullable();
            $table->string('canonical_filename')->nullable();
            $table->string('disk')->default('public');
            $table->string('path')->nullable();
            $table->string('media_type')->default('image');
            $table->string('mime_type')->default('image/jpeg');
            $table->string('extension')->default('jpg');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->nullable();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->nullable();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('theme_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->boolean('is_active')->default(false);
            $table->json('conditions')->nullable();
            $table->timestamps();
        });

        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('status')->default('draft');
            $table->string('heading')->nullable();
            $table->text('body_content')->nullable();
            $table->unsignedBigInteger('image_media_id')->nullable();
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('trigger_type')->default('delay');
            $table->unsignedInteger('trigger_delay_seconds')->default(0);
            $table->unsignedInteger('trigger_scroll_percent')->default(0);
            $table->unsignedInteger('suppress_days')->default(0);
            $table->boolean('show_on_mobile')->default(true);
            $table->boolean('show_to_returning')->default(false);
            $table->json('excluded_pages')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query');
            $table->unsignedInteger('results_count')->default(0);
            $table->string('session_id')->nullable();
            $table->string('page_context')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }
}
