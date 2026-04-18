<?php

namespace Tests\Feature;

use App\Models\ContentBlock;
use App\Models\MediaAsset;
use App\Models\PageBlock;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BlockRendererTest extends TestCase
{
    public function test_block_renderer_loads_top_level_unified_block_views(): void
    {
        $block = new PageBlock([
            'block_type' => 'feature_list',
            'category' => 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'heading' => 'Why Choose Us',
                'features' => [
                    ['title' => 'Craftsmanship', 'description' => 'Built to last.'],
                ],
            ],
        ]);

        $html = Blade::render('<x-frontend.block-renderer :block="$block" :context="$context" />', [
            'block' => $block,
            'context' => [],
        ]);

        $this->assertStringContainsString('Why Choose Us', $html);
        $this->assertStringContainsString('Craftsmanship', $html);
    }

    public function test_legacy_content_block_renderer_parses_dynamic_context_values(): void
    {
        $blocks = collect([
            new ContentBlock([
                'block_type' => 'feature_list',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Serving {city.name}',
                    'features' => [
                        ['title' => 'Reliable', 'description' => 'Responsive service.'],
                    ],
                ],
            ]),
        ]);

        $html = Blade::render('<x-frontend.content-blocks :blocks="$blocks" :context="$context" />', [
            'blocks' => $blocks,
            'context' => [
                'city' => ['name' => 'Calgary'],
            ],
        ]);

        $this->assertStringContainsString('Serving Calgary', $html);
    }

    public function test_block_renderer_outputs_custom_id_attributes_and_animation_metadata(): void
    {
        $block = new PageBlock([
            'block_type' => 'heading',
            'category' => 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'custom_id' => 'promo-section',
            'attributes' => [
                'data-tracking-id' => 'promo-hero',
                'aria-label' => 'Promotional heading',
            ],
            'animation' => 'fade-up',
            'content' => [
                'text' => 'Spring Promotion',
                'level' => 'h2',
                'align' => 'left',
            ],
        ]);

        $html = Blade::render('<x-frontend.block-renderer :block="$block" :context="$context" />', [
            'block' => $block,
            'context' => [],
        ]);

        $this->assertStringContainsString('id="promo-section"', $html);
        $this->assertStringContainsString('data-tracking-id="promo-hero"', $html);
        $this->assertStringContainsString('aria-label="Promotional heading"', $html);
        $this->assertStringContainsString('data-animate="fade-up"', $html);
        $this->assertStringContainsString('Spring Promotion', $html);
    }

    public function test_two_column_block_respects_explicit_child_slot_assignments(): void
    {
        $leftChild = new PageBlock([
            'block_type' => 'heading',
            'category' => 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'text' => 'Left Heading',
                'level' => 'h2',
                '_layout_slot' => 'left',
            ],
        ]);

        $rightChild = new PageBlock([
            'block_type' => 'paragraph',
            'category' => 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'text' => 'Right Paragraph',
                '_layout_slot' => 'right',
            ],
        ]);

        $block = new PageBlock([
            'block_type' => 'two_column',
            'category' => 'layout',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'ratio' => '1:1',
                'gap' => 'md',
            ],
        ]);
        $block->setRelation('children', collect([$leftChild, $rightChild]));

        $html = Blade::render('<x-frontend.block-renderer :block="$block" :context="$context" />', [
            'block' => $block,
            'context' => [],
        ]);

        $this->assertMatchesRegularExpression('/order-1.*Left Heading/s', $html);
        $this->assertMatchesRegularExpression('/order-2.*Right Paragraph/s', $html);
    }

    public function test_services_grid_supports_editorial_variant_and_view_all_link(): void
    {
        $category = (object) [
            'id' => 10,
            'name' => 'Hardscaping',
            'slug_final' => 'hardscaping',
            'sort_order' => 1,
            'short_description' => 'Premium exterior construction.',
            'icon' => 'hammer',
        ];

        $service = (object) [
            'name' => 'Porcelain Patios',
            'slug_final' => 'porcelain-patios',
            'category_id' => 10,
            'category' => $category,
            'service_summary' => 'Large-format porcelain installations.',
            'icon' => 'layout-grid',
            'public_url' => '/services/hardscaping/porcelain-patios',
        ];

        $html = Blade::render(
            file_get_contents(resource_path('views/frontend/blocks/partials/services-grid.blade.php')),
            [
                'content' => [
                    'eyebrow' => 'Core Disciplines',
                    'heading' => 'Architectural Hardscaping',
                    'variant' => 'editorial',
                    'tone' => 'light',
                    'show_category_nav' => true,
                    'show_view_all' => true,
                    'view_all_text' => 'View All Services',
                    'view_all_url' => '/services',
                ],
                'data' => collect([$service]),
                'context' => [],
            ]
        );

        $this->assertStringContainsString('Core Disciplines', $html);
        $this->assertStringContainsString('Architectural Hardscaping', $html);
        $this->assertStringContainsString('/services/hardscaping/porcelain-patios', $html);
        $this->assertStringContainsString('View All Services', $html);
    }

    public function test_city_grid_strip_variant_renders_city_strip_links(): void
    {
        $oakville = (object) [
            'name' => 'Oakville',
            'slug_final' => 'oakville',
            'region_name' => 'Halton',
        ];

        $mississauga = (object) [
            'name' => 'Mississauga',
            'slug_final' => 'mississauga',
            'region_name' => 'Peel',
        ];

        $html = Blade::render(
            file_get_contents(resource_path('views/frontend/blocks/partials/city-grid.blade.php')),
            [
                'content' => [
                    'eyebrow' => 'Serving The GTA',
                    'heading' => 'Premier Service Areas',
                    'layout' => 'strip',
                    'tone' => 'light',
                    'show_view_all' => true,
                    'view_all_text' => 'View All Areas',
                    'view_all_url' => '/locations',
                ],
                'data' => collect([$oakville, $mississauga]),
            ]
        );

        $this->assertStringContainsString('Serving The GTA', $html);
        $this->assertStringContainsString('/landscaping-oakville', $html);
        $this->assertStringContainsString('/landscaping-mississauga', $html);
        $this->assertStringContainsString('View All Areas', $html);
    }

    public function test_block_renderer_applies_semantic_surface_and_shell_styles(): void
    {
        $block = new PageBlock([
            'block_type' => 'heading',
            'category' => 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'text' => 'Luxury Surface',
                'level' => 'h2',
                'align' => 'left',
            ],
            'styles' => [
                'desktop' => [
                    'surface_style' => 'glass-light',
                    'glass_effect' => 'strong',
                    'section_shell' => 'luxury-panel',
                    'divider_style' => 'gold-bottom',
                    'spacing_preset' => 'feature',
                ],
            ],
        ]);

        $html = Blade::render('<x-frontend.block-renderer :block="$block" :context="$context" />', [
            'block' => $block,
            'context' => [],
        ]);

        $this->assertStringContainsString('backdrop-filter:blur(18px)', $html);
        $this->assertStringContainsString('border-radius:2rem', $html);
        $this->assertStringContainsString('border-bottom:1px solid rgba(164, 113, 72, 0.55)', $html);
        $this->assertStringContainsString('padding-top:7rem', $html);
    }

    public function test_theme_header_shell_renders_slot_children_and_mobile_overlay_container(): void
    {
        $leftChild = new PageBlock([
            'block_type' => 'heading',
            'category' => 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'text' => 'Brand',
                'level' => 'h3',
                '_layout_slot' => 'left',
            ],
        ]);

        $rightChild = new PageBlock([
            'block_type' => 'cta_button',
            'category' => 'interactive',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'text' => 'Request Consultation',
                'url' => '/contact',
                'style' => 'primary',
                '_layout_slot' => 'right',
            ],
        ]);

        $mobileChild = new PageBlock([
            'block_type' => 'paragraph',
            'category' => 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'text' => 'Mobile navigation copy',
                '_layout_slot' => 'mobile',
            ],
        ]);

        $block = new PageBlock([
            'block_type' => 'theme_header_shell',
            'category' => 'theme',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'mode' => 'glass',
                'tone' => 'dark',
                'sticky' => true,
                'mobile_menu_label' => 'Open',
            ],
        ]);
        $block->setRelation('children', collect([$leftChild, $rightChild, $mobileChild]));

        $html = Blade::render('<x-frontend.block-renderer :block="$block" :context="$context" />', [
            'block' => $block,
            'context' => [],
        ]);

        $this->assertStringContainsString('data-header-mode="glass"', $html);
        $this->assertStringContainsString('Brand', $html);
        $this->assertStringContainsString('Request Consultation', $html);
        $this->assertStringContainsString('theme-header-mobile-overlay', $html);
        $this->assertStringContainsString('Mobile navigation copy', $html);
    }

    public function test_editorial_split_feature_renders_media_features_and_cta(): void
    {
        $media = new MediaAsset([
            'id' => 42,
            'path' => 'feature.jpg',
            'default_alt_text' => 'Precision landscape project',
        ]);

        $html = Blade::render(
            file_get_contents(resource_path('views/frontend/blocks/editorial-split-feature.blade.php')),
            [
                'content' => [
                    'eyebrow' => 'Elite Standards',
                    'heading' => 'Architectural Integrity',
                    'description' => 'A composed split-feature layout.',
                    'media_id' => 42,
                    'feature_layout' => 'stacked',
                    'features' => [
                        ['icon' => 'gem', 'title' => 'Premium Materials', 'description' => 'Specified for endurance.'],
                    ],
                    'cta_text' => 'Explore Process',
                    'cta_url' => '/process',
                ],
                'mediaLookup' => collect([42 => $media]),
            ]
        );

        $this->assertStringContainsString('Elite Standards', $html);
        $this->assertStringContainsString('Architectural Integrity', $html);
        $this->assertStringContainsString('Premium Materials', $html);
        $this->assertStringContainsString('/process', $html);
        $this->assertStringContainsString('feature.jpg', $html);
    }

    public function test_standard_portfolio_gallery_block_renders_partial_instead_of_legacy_section_view(): void
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('hero_media_id')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->date('completion_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('portfolio_projects')->insert([
            'title' => 'Executive Modernism',
            'slug' => 'executive-modernism',
            'description' => 'A premium landscape build.',
            'status' => 'published',
            'is_featured' => true,
            'completion_date' => '2026-04-01',
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $block = new PageBlock([
            'block_type' => 'portfolio_gallery',
            'category' => 'data',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'eyebrow' => 'Completed Works',
                'heading' => 'Portfolio',
                'subtitle' => 'Curated project highlights.',
                'layout' => 'grid',
                'columns' => '3',
                'variant' => 'editorial',
                'tone' => 'light',
                'show_view_all' => true,
                'view_all_text' => 'View More Projects',
                'view_all_url' => '/portfolio',
            ],
        ]);

        $html = Blade::render('<x-frontend.block-renderer :block="$block" :context="$context" />', [
            'block' => $block,
            'context' => [],
        ]);

        $this->assertStringContainsString('Completed Works', $html);
        $this->assertStringContainsString('Executive Modernism', $html);
        $this->assertStringContainsString('/portfolio/executive-modernism', $html);
        $this->assertStringContainsString('View More Projects', $html);

        Schema::dropIfExists('portfolio_projects');
    }
}
