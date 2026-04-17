<?php

namespace Tests\Feature;

use App\Console\Services\HomePageBlueprintService;
use App\Models\PageBlock;
use App\Models\Setting;
use App\Services\BlockBuilderService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomePageBlueprintServiceTest extends TestCase
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

        $this->createBlueprintTables();
    }

    public function test_build_uses_published_project_media_for_homepage_showcase_sections(): void
    {
        DB::table('portfolio_projects')->insert([
            [
                'id' => 1,
                'hero_media_id' => 91,
                'status' => 'published',
                'is_featured' => 1,
                'completion_date' => '2026-04-01',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'hero_media_id' => 92,
                'status' => 'published',
                'is_featured' => 0,
                'completion_date' => '2026-03-20',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $blocks = app(HomePageBlueprintService::class)->build();

        $this->assertSame('hero', $blocks[0]['block_type']);
        $this->assertSame(91, $blocks[0]['content']['hero_media_id']);
        $this->assertSame([92], $blocks[0]['content']['extra_image_ids']);
        $this->assertSame('portfolio_gallery', $blocks[2]['block_type']);
        $this->assertSame('editorial_split_feature', $blocks[3]['block_type']);
        $this->assertSame(92, $blocks[3]['content']['media_id']);
        $this->assertSame('consultation', $blocks[6]['content']['form_slug']);
    }

    public function test_scaffold_skips_when_homepage_content_already_exists_and_replace_is_not_requested(): void
    {
        PageBlock::create([
            'page_type' => 'home',
            'page_id' => null,
            'block_type' => 'heading',
            'category' => 'content',
            'sort_order' => 1,
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => ['text' => 'Existing Home Content'],
            'styles' => BlockBuilderService::styleDefaults(),
        ]);

        $result = app(HomePageBlueprintService::class)->scaffold();

        $this->assertFalse($result['applied']);
        $this->assertSame('existing_content', $result['reason']);
        $this->assertSame(1, $result['existing_unified_blocks']);
        $this->assertCount(1, PageBlock::forPage('home', null)->get());
    }

    public function test_scaffold_can_replace_existing_homepage_content_and_seed_seo_defaults(): void
    {
        PageBlock::create([
            'page_type' => 'home',
            'page_id' => null,
            'block_type' => 'paragraph',
            'category' => 'content',
            'sort_order' => 1,
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => ['text' => 'Outdated Home Copy'],
            'styles' => BlockBuilderService::styleDefaults(),
        ]);

        $result = app(HomePageBlueprintService::class)->scaffold(true);

        $this->assertTrue($result['applied']);
        $this->assertTrue($result['replaced']);
        $this->assertSame(7, $result['block_count']);
        $this->assertSame(
            ['hero', 'services_grid', 'portfolio_gallery', 'trust_badges', 'process_steps', 'city_grid', 'form_block'],
            PageBlock::forPage('home', null)->topLevel()->orderBy('sort_order')->pluck('block_type')->all()
        );
        $this->assertSame(
            'Lush Landscapes | Luxury Landscape Construction & Hardscape Design GTA',
            Setting::get('seo_home_title')
        );
        $this->assertSame('contact', PageBlock::forPage('home', null)->topLevel()->orderByDesc('sort_order')->value('custom_id'));
    }

    protected function tearDown(): void
    {
        Setting::flushCache();
        Schema::dropAllTables();

        parent::tearDown();
    }

    private function createBlueprintTables(): void
    {
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
    }
}
