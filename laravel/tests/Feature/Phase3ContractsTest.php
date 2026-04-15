<?php

namespace Tests\Feature;

use Tests\TestCase;

class Phase3ContractsTest extends TestCase
{
    public function test_hero_supports_overlay_and_alignment_fields(): void
    {
        $fields = collect(config('blocks.types.hero.content_fields'))->pluck('key')->all();

        $this->assertContains('overlay_preset', $fields);
        $this->assertContains('overlay_opacity', $fields);
        $this->assertContains('align', $fields);
        $this->assertContains('height', $fields);

        $defaults = config('blocks.types.hero.defaults');
        $this->assertSame('editorial', $defaults['variant']);
        $this->assertSame('gradient', $defaults['overlay_preset']);
        $this->assertSame('viewport', $defaults['height']);
        $this->assertSame('/contact', $defaults['cta_primary_url']);
    }

    public function test_portfolio_gallery_supports_compact_variant(): void
    {
        $variantField = collect(config('blocks.types.portfolio_gallery.content_fields'))
            ->firstWhere('key', 'variant');

        $this->assertIsArray($variantField);
        $this->assertArrayHasKey('options', $variantField);
        $this->assertArrayHasKey('compact', $variantField['options']);
    }

    public function test_area_served_supports_inline_layout_mode(): void
    {
        $layoutField = collect(config('blocks.types.area_served.content_fields'))
            ->firstWhere('key', 'layout');

        $this->assertIsArray($layoutField);
        $this->assertArrayHasKey('options', $layoutField);
        $this->assertSame(['grid' => 'Grid', 'inline' => 'Inline Text'], $layoutField['options']);

        $defaults = config('blocks.types.area_served.defaults');
        $this->assertSame('grid', $defaults['layout']);
    }

    public function test_frontend_views_do_not_include_quote_led_defaults(): void
    {
        $root = base_path('resources/views/frontend');
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));

        $matches = [];
        foreach ($iterator as $file) {
            if (! $file instanceof \SplFileInfo || ! $file->isFile()) {
                continue;
            }

            $path = (string) $file->getPathname();
            if (! str_ends_with($path, '.blade.php')) {
                continue;
            }

            $contents = file_get_contents($path);
            if ($contents === false) {
                continue;
            }

            if (
                str_contains($contents, 'Free Estimate')
                || str_contains($contents, 'Free Quote')
                || str_contains($contents, 'Get a Quote')
                || str_contains($contents, 'Request a Quote')
                || str_contains($contents, 'Quote Request')
                || str_contains($contents, 'no obligation')
                || str_contains($contents, 'No obligation')
                || str_contains($contents, 'Seasonal Deals')
                || str_contains($contents, 'transparent pricing')
                || str_contains($contents, 'Transparent pricing')
            ) {
                $matches[] = $path;
            }
        }

        $this->assertSame([], $matches);
    }

    public function test_dynamic_loop_missing_template_state_is_visible_in_admin(): void
    {
        $path = base_path('resources/views/frontend/blocks/partials/dynamic-loop.blade.php');
        $contents = file_get_contents($path);

        $this->assertIsString($contents);
        $this->assertStringContainsString('Missing template card (dynamic_loop.template_id)', $contents);
    }

    public function test_premium_blocks_use_frontend_media_component(): void
    {
        $paths = [
            base_path('resources/views/frontend/blocks/editorial-split-feature.blade.php'),
            base_path('resources/views/frontend/blocks/cards-grid.blade.php'),
            base_path('resources/views/frontend/blocks/image-text.blade.php'),
            base_path('resources/views/frontend/blocks/partials/_portfolio-card.blade.php'),
            base_path('resources/views/frontend/blocks/partials/blog-strip.blade.php'),
            base_path('resources/views/frontend/blocks/partials/portfolio-directory.blade.php'),
        ];

        foreach ($paths as $path) {
            $contents = file_get_contents($path);
            $this->assertIsString($contents);
            $this->assertStringContainsString('<x-frontend.media', $contents, $path);
        }
    }

    public function test_motion_preset_none_is_supported_in_frontend_js(): void
    {
        $path = base_path('resources/js/app.js');
        $contents = file_get_contents($path);

        $this->assertIsString($contents);
        $this->assertStringContainsString('none:', $contents);
        $this->assertStringContainsString("motionPreset !== 'none'", $contents);
    }
}
