<?php

namespace Tests\Feature;

use App\Console\Services\ListingPageBlueprintService;
use App\Models\PageBlock;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ListingPageBlueprintServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        $this->createBuilderTables();
    }

    public function test_build_portfolio_index_uses_directory_block(): void
    {
        DB::table('portfolio_projects')->insert([
            [
                'id' => 1,
                'hero_media_id' => 501,
                'status' => 'published',
                'is_featured' => 1,
                'completion_date' => '2026-04-10',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $blocks = app(ListingPageBlueprintService::class)->buildSingletonPage('portfolio-index');

        $this->assertSame(
            ['hero', 'portfolio_directory', 'cta_section'],
            array_column($blocks, 'block_type')
        );
        $this->assertSame(501, $blocks[0]['content']['hero_media_id']);
        $this->assertSame(12, $blocks[1]['data_source']['limit']);
    }

    public function test_scaffold_populates_singleton_and_taxonomy_listing_pages(): void
    {
        DB::table('portfolio_projects')->insert([
            [
                'id' => 1,
                'hero_media_id' => 601,
                'status' => 'published',
                'is_featured' => 1,
                'completion_date' => '2026-04-10',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('portfolio_categories')->insert([
            'id' => 10,
            'name' => 'Luxury Pools',
            'slug' => 'luxury-pools',
            'short_description' => 'Category intro',
            'status' => 'published',
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('service_categories')->insert([
            'id' => 30,
            'name' => 'Hardscaping',
            'system_slug' => 'hardscaping',
            'slug_final' => 'hardscaping',
            'short_description' => 'Service category intro',
            'status' => 'published',
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('blog_categories')->insert([
            'id' => 20,
            'name' => 'Planning Guides',
            'slug' => 'planning-guides',
            'short_description' => 'Blog category intro',
            'status' => 'published',
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $result = app(ListingPageBlueprintService::class)->scaffold(true);

        $this->assertTrue($result['singleton_pages']['services-hub']['applied']);
        $this->assertTrue($result['singleton_pages']['blog-index']['applied']);
        $this->assertCount(5, PageBlock::forPage('services_hub', null)->topLevel()->get());
        $this->assertSame(
            ['hero', 'services_grid', 'cta_section'],
            PageBlock::forPage('service_category', 30)->topLevel()->orderBy('sort_order')->pluck('block_type')->all()
        );
        $this->assertSame(
            ['portfolio_directory', 'cta_section'],
            PageBlock::forPage('portfolio_category', 10)->topLevel()->orderBy('sort_order')->pluck('block_type')->all()
        );
        $this->assertSame(
            ['blog_directory', 'cta_section'],
            PageBlock::forPage('blog_category', 20)->topLevel()->orderBy('sort_order')->pluck('block_type')->all()
        );
    }

    public function test_scaffold_command_reports_results_without_error(): void
    {
        $exitCode = Artisan::call('app:scaffold-listing-blueprints');

        $this->assertSame(0, $exitCode, Artisan::output());
        $this->assertGreaterThan(0, PageBlock::query()->count());
    }

    protected function tearDown(): void
    {
        Schema::dropAllTables();

        parent::tearDown();
    }

    private function createBuilderTables(): void
    {
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page_type', 60);
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('block_type', 80);
            $table->string('category', 30)->default('content');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->boolean('show_on_tablet')->default(true);
            $table->boolean('show_on_mobile')->default(true);
            $table->timestamp('visible_from')->nullable();
            $table->timestamp('visible_until')->nullable();
            $table->json('content')->nullable();
            $table->json('data_source')->nullable();
            $table->json('styles')->nullable();
            $table->string('custom_id', 100)->nullable();
            $table->json('attributes')->nullable();
            $table->string('animation', 40)->nullable();
            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page_type', 50);
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('section_key', 100);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->boolean('show_on_mobile')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('page_content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page_type', 60);
            $table->unsignedBigInteger('page_id');
            $table->string('section_key', 60)->nullable();
            $table->string('block_type', 60);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->json('content')->nullable();
            $table->timestamps();
        });

        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hero_media_id')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->date('completion_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('portfolio_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('image_media_id')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('schema_type')->nullable();
            $table->json('schema_json')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('image_media_id')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('schema_type')->nullable();
            $table->json('schema_json')->nullable();
            $table->string('language')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('system_slug')->nullable();
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->nullable();
            $table->string('navigation_label')->nullable();
            $table->text('short_description')->nullable();
            $table->text('long_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->json('schema_json')->nullable();
            $table->json('keywords_json')->nullable();
            $table->unsignedBigInteger('hero_media_id')->nullable();
            $table->string('icon')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
}
