<?php

namespace Tests\Feature;

use App\Services\BlockBuilderService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class BlockBuilderMetadataTest extends TestCase
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

        $this->createBlockTables();
    }

    public function test_save_unified_blocks_persists_metadata_attributes_and_data_source(): void
    {
        BlockBuilderService::saveUnifiedBlocks('static_page', 42, [
            [
                'block_type' => 'dynamic_loop',
                'category' => 'data',
                'is_enabled' => true,
                'show_on_desktop' => true,
                'show_on_tablet' => true,
                'show_on_mobile' => true,
                'visible_from' => '2026-04-09T08:30',
                'visible_until' => '2026-04-30T20:15',
                'content' => [
                    'heading' => 'Featured Work',
                    'template_id' => 1,
                    'data_model' => 'App\\Models\\PortfolioProject',
                ],
                'data_source' => [
                    'model' => 'auto',
                    'scope' => 'published',
                    'filters' => [
                        'category_id' => 'auto',
                        'is_featured' => true,
                    ],
                    'limit' => '6',
                    'order_by' => 'completion_date',
                    'order_dir' => 'desc',
                    'manual_ids' => [5, 8, 13],
                ],
                'styles' => BlockBuilderService::styleDefaults(),
                'custom_id' => 'featured-work',
                'attributes' => [
                    'data-tracking-id' => 'featured-loop',
                ],
                'animation' => 'fade-up',
            ],
        ]);

        $stored = DB::table('page_blocks')->whereNull('parent_id')->first();
        $this->assertNotNull($stored);
        $this->assertSame('featured-work', $stored->custom_id);
        $this->assertSame('fade-up', $stored->animation);
        $this->assertSame(['data-tracking-id' => 'featured-loop'], json_decode($stored->attributes, true));
        $this->assertSame('App\\Models\\PortfolioProject', json_decode($stored->content, true)['data_model']);
        $this->assertSame(['category_id' => 'auto', 'is_featured' => true], json_decode($stored->data_source, true)['filters']);
        $this->assertSame([5, 8, 13], json_decode($stored->data_source, true)['manual_ids']);

        $editorBlocks = BlockBuilderService::getUnifiedBlocks('static_page', 42);
        $this->assertCount(1, $editorBlocks);
        $this->assertSame('featured-work', $editorBlocks->first()['custom_id']);
        $this->assertSame('fade-up', $editorBlocks->first()['animation']);
        $this->assertSame(['data-tracking-id' => 'featured-loop'], $editorBlocks->first()['attributes']);
    }

    public function test_save_unified_blocks_persists_nested_child_blocks_and_returns_editor_tree(): void
    {
        BlockBuilderService::saveUnifiedBlocks('static_page', 84, [
            [
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
                'styles' => BlockBuilderService::styleDefaults(),
                'children' => [
                    [
                        'block_type' => 'heading',
                        'category' => 'content',
                        'is_enabled' => true,
                        'show_on_desktop' => true,
                        'show_on_tablet' => true,
                        'show_on_mobile' => true,
                        'content' => [
                            'text' => 'Left Column Heading',
                            'level' => 'h2',
                            '_layout_slot' => 'left',
                        ],
                        'styles' => BlockBuilderService::styleDefaults(),
                    ],
                    [
                        'block_type' => 'paragraph',
                        'category' => 'content',
                        'is_enabled' => true,
                        'show_on_desktop' => true,
                        'show_on_tablet' => true,
                        'show_on_mobile' => true,
                        'content' => [
                            'text' => 'Right column body copy.',
                            '_layout_slot' => 'right',
                        ],
                        'styles' => BlockBuilderService::styleDefaults(),
                    ],
                ],
            ],
        ]);

        $this->assertSame(3, DB::table('page_blocks')->count());
        $parent = DB::table('page_blocks')->whereNull('parent_id')->first();
        $children = DB::table('page_blocks')->where('parent_id', $parent->id)->orderBy('sort_order')->get();

        $this->assertCount(2, $children);
        $this->assertSame('heading', $children[0]->block_type);
        $this->assertSame('left', json_decode($children[0]->content, true)['_layout_slot']);
        $this->assertSame('right', json_decode($children[1]->content, true)['_layout_slot']);

        $editorBlocks = BlockBuilderService::getUnifiedBlocks('static_page', 84);
        $this->assertCount(1, $editorBlocks);
        $this->assertCount(2, $editorBlocks->first()['children']);
        $this->assertSame('Left Column Heading', $editorBlocks->first()['children'][0]['content']['text']);
        $this->assertSame('right', $editorBlocks->first()['children'][1]['content']['_layout_slot']);
    }

    public function test_phase_b_blocks_can_save_and_reload_via_unified_builder_contract(): void
    {
        $types = collect(BlockBuilderService::allTypes())->pluck('key')->all();

        $this->assertContains('marquee_strip', $types);
        $this->assertContains('parallax_media_band', $types);
        $this->assertContains('authority_grid', $types);
        $this->assertContains('service_area_enclave', $types);
        $this->assertContains('split_consultation_panel', $types);

        $payload = [
            [
                'block_type' => 'marquee_strip',
                'category' => 'content',
                'is_enabled' => true,
                'content' => [
                    'text_items' => 'A, B, C',
                    'separator_style' => 'dot',
                    'speed' => 'slow',
                    'direction' => 'left',
                    'tone' => 'dark',
                ],
                'styles' => BlockBuilderService::styleDefaults(),
            ],
            [
                'block_type' => 'parallax_media_band',
                'category' => 'media',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Headline',
                    'subheadline' => 'Sub',
                    'media_id' => null,
                    'video_url' => '',
                    'parallax_intensity' => 'subtle',
                    'overlay_preset' => 'dark',
                ],
                'styles' => BlockBuilderService::styleDefaults(),
            ],
            [
                'block_type' => 'authority_grid',
                'category' => 'content',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Standards',
                    'heading' => 'Built to Last',
                    'introduction' => 'Intro',
                    'card_skin' => 'premium-bordered',
                    'items' => [
                        ['icon' => 'shield-check', 'title' => 'Warranty', 'description' => '10 years'],
                    ],
                ],
                'styles' => BlockBuilderService::styleDefaults(),
            ],
            [
                'block_type' => 'service_area_enclave',
                'category' => 'data',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Areas',
                    'heading' => 'Serving',
                    'support_copy' => '',
                    'presentation_mode' => 'text-led',
                ],
                'styles' => BlockBuilderService::styleDefaults(),
            ],
            [
                'block_type' => 'split_consultation_panel',
                'category' => 'interactive',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Get Started',
                    'heading' => 'Book a Consultation',
                    'editorial_copy' => 'Copy',
                    'trust_lines' => 'A, B',
                    'media_id' => null,
                    'form_slug' => 'contact-us',
                    'tone' => 'dark',
                ],
                'styles' => BlockBuilderService::styleDefaults(),
            ],
        ];

        BlockBuilderService::saveUnifiedBlocks('static_page', 99, $payload);

        $this->assertSame(5, DB::table('page_blocks')->whereNull('parent_id')->count());

        $editorBlocks = BlockBuilderService::getUnifiedBlocks('static_page', 99);
        $this->assertCount(5, $editorBlocks);
        $this->assertSame(
            ['marquee_strip', 'parallax_media_band', 'authority_grid', 'service_area_enclave', 'split_consultation_panel'],
            $editorBlocks->pluck('block_type')->all()
        );

        $split = $editorBlocks->last();
        $this->assertSame('contact-us', $split['content']['form_slug']);
        $this->assertSame('Book a Consultation', $split['content']['heading']);
    }

    private function createBlockTables(): void
    {
        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_content_blocks');

        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page_type');
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('block_type');
            $table->string('category')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->boolean('show_on_tablet')->default(true);
            $table->boolean('show_on_mobile')->default(true);
            $table->timestamp('visible_from')->nullable();
            $table->timestamp('visible_until')->nullable();
            $table->json('content')->nullable();
            $table->json('data_source')->nullable();
            $table->json('styles')->nullable();
            $table->string('custom_id')->nullable();
            $table->json('attributes')->nullable();
            $table->string('animation')->nullable();
            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page_type');
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('section_key');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->boolean('show_on_mobile')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('page_content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page_type');
            $table->unsignedBigInteger('page_id')->default(0);
            $table->string('section_key')->nullable();
            $table->string('block_type');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->json('content')->nullable();
            $table->timestamps();
        });
    }
}
