<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Services\ThemePresentationService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ThemePresentationServiceTest extends TestCase
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

        $this->createThemeTables();
    }

    public function test_theme_service_builds_replaced_copyright_and_filtered_social_links(): void
    {
        Setting::set('site_name', 'Lush Landscape');
        Setting::set('footer_copyright_text', '© {year} {site_name}. All rights reserved.');
        Setting::set('facebook_url', 'https://facebook.com/lush');
        Setting::set('instagram_url', '');
        Setting::set('youtube_url', 'https://youtube.com/lush');

        $service = app(ThemePresentationService::class);

        $this->assertSame('© '.date('Y').' Lush Landscape. All rights reserved.', $service->copyrightText());
        $this->assertCount(2, $service->socialLinks());
        $this->assertSame('facebook', $service->socialLinks()[0]['platform']);
        $this->assertSame('youtube', $service->socialLinks()[1]['platform']);
    }

    public function test_theme_service_nav_collections_only_return_published_records(): void
    {
        ServiceCategory::create([
            'name' => 'Patios',
            'slug_final' => 'patios',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        ServiceCategory::create([
            'name' => 'Hidden Category',
            'slug_final' => 'hidden-category',
            'status' => 'draft',
            'sort_order' => 2,
        ]);

        City::create([
            'name' => 'Oakville',
            'slug_final' => 'oakville',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        City::create([
            'name' => 'Hidden City',
            'slug_final' => 'hidden-city',
            'status' => 'draft',
            'sort_order' => 2,
        ]);

        $service = app(ThemePresentationService::class);

        $this->assertSame(['Patios'], $service->navCategories()->pluck('name')->all());
        $this->assertSame(['Oakville'], $service->navCities()->pluck('name')->all());
    }

    public function test_theme_meta_data_supports_paragraph_display_for_footer_brand_copy(): void
    {
        Setting::set('footer_tagline', 'Luxury outdoor construction with architectural precision.');

        $html = Blade::render(
            file_get_contents(resource_path('views/frontend/blocks/partials/theme-meta-data.blade.php')),
            [
                'content' => [
                    'meta_key' => 'footer_tagline',
                    'display' => 'paragraph',
                    'tone' => 'light',
                    'icon' => 'none',
                    'prefix' => 'About Lush',
                ],
            ]
        );

        $this->assertStringContainsString('About Lush', $html);
        $this->assertStringContainsString('Luxury outdoor construction with architectural precision.', $html);
        $this->assertStringContainsString('max-w-md', $html);
        $this->assertStringContainsString('text-sm leading-relaxed', $html);
    }

    public function test_theme_service_sanitizes_quote_led_cta_and_low_tier_newsletter_heading(): void
    {
        Setting::set('nav_cta_text', 'Get a Quote');
        Setting::set('nav_cta_url', '/consultation');
        Setting::set('footer_newsletter_heading', 'Get Landscaping Tips & Seasonal Deals');

        $service = app(ThemePresentationService::class);

        // Test CTA fallback (should allow /consultation because it's now consultation)
        $this->assertSame('/consultation', $service->ctaUrl());
        $this->assertSame('Book a Consultation', $service->ctaText());

        // Test Newsletter heading fallback
        $this->assertSame('Exclusive Landscape Insights', $service->newsletterHeading());
    }

    protected function tearDown(): void
    {
        Setting::flushCache();
        Schema::dropAllTables();

        parent::tearDown();
    }

    private function createThemeTables(): void
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

        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('system_slug')->nullable();
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->nullable();
            $table->string('navigation_label')->nullable();
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
            $table->string('navigation_label')->nullable();
            $table->string('region_name')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }
}
