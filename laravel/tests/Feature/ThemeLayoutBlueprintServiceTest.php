<?php

namespace Tests\Feature;

use App\Console\Services\ThemeLayoutBlueprintService;
use App\Models\Setting;
use App\Models\ThemeLayout;
use App\Services\BlockBuilderService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ThemeLayoutBlueprintServiceTest extends TestCase
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

    public function test_scaffold_creates_builder_safe_header_and_footer_drafts(): void
    {
        Setting::set('nav_cta_text', 'Request Quote');
        Setting::set('nav_cta_url', '/consultation');

        $layouts = app(ThemeLayoutBlueprintService::class)->scaffold();

        $this->assertCount(5, ThemeLayout::all());
        $this->assertFalse($layouts['header']->is_active);
        $this->assertFalse($layouts['footer']->is_active);

        $headerBlocks = BlockBuilderService::getUnifiedBlocks('theme_layout', $layouts['header']->id);
        $this->assertCount(1, $headerBlocks);
        $this->assertSame('theme_header_shell', $headerBlocks->first()['block_type']);
        $this->assertSame('glass', data_get($headerBlocks->first(), 'content.mode'));
        $this->assertSame('/contact', data_get($headerBlocks->first(), 'children.2.content.primary_url'));
        $this->assertSame(
            ['left', 'center', 'right'],
            collect($headerBlocks->first()['children'])->map(fn (array $child) => data_get($child, 'content._layout_slot'))->all()
        );

        $footerBlocks = BlockBuilderService::getUnifiedBlocks('theme_layout', $layouts['footer']->id);
        $this->assertSame(
            ['theme_newsletter_panel', 'two_column', 'theme_legal_bar'],
            $footerBlocks->pluck('block_type')->all()
        );

        $footerColumns = $footerBlocks->firstWhere('block_type', 'two_column');
        $this->assertNotNull($footerColumns);
        $this->assertContains(
            'theme_meta_data',
            collect($footerColumns['children'])->pluck('block_type')->all()
        );
        $this->assertSame(
            'paragraph',
            data_get(
                collect($footerColumns['children'])->firstWhere('block_type', 'theme_meta_data'),
                'content.display'
            )
        );
    }

    public function test_scaffold_command_refreshes_existing_layouts_without_duplicates_and_can_activate(): void
    {
        app(ThemeLayoutBlueprintService::class)->scaffold();
        $originalIds = ThemeLayout::query()->pluck('id', 'name')->all();

        $exitCode = Artisan::call('app:scaffold-theme-layouts', ['--activate' => true]);

        $this->assertSame(0, $exitCode, Artisan::output());
        $this->assertCount(5, ThemeLayout::all());
        $this->assertSame($originalIds, ThemeLayout::query()->pluck('id', 'name')->all());
        $this->assertTrue((bool) ThemeLayout::query()->where('name', ThemeLayoutBlueprintService::HEADER_LAYOUT_NAME)->value('is_active'));
        $this->assertTrue((bool) ThemeLayout::query()->where('name', ThemeLayoutBlueprintService::FOOTER_LAYOUT_NAME)->value('is_active'));
    }

    protected function tearDown(): void
    {
        Setting::flushCache();
        Schema::dropAllTables();

        parent::tearDown();
    }

    private function createBuilderTables(): void
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

        Schema::create('theme_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->index();
            $table->boolean('is_active')->default(true);
            $table->json('conditions')->nullable();
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
    }
}
