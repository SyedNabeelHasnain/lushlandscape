<?php

namespace Tests\Feature;

use App\Models\PageBlock;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class ContentBlockExportControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array',
            'session.driver' => 'array',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        $this->createTables();
        $this->withoutMiddleware();
    }

    public function test_export_strips_database_ids_and_legacy_markers_from_builder_payload(): void
    {
        $parent = PageBlock::create([
            'page_type' => 'service_category',
            'page_id' => 5,
            'block_type' => 'hero',
            'category' => 'data',
            'sort_order' => 1,
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'heading' => 'Category Hero',
                '_legacy_content_block_id' => 99,
            ],
        ]);

        PageBlock::create([
            'page_type' => 'service_category',
            'page_id' => 5,
            'block_type' => 'heading',
            'category' => 'content',
            'parent_id' => $parent->id,
            'sort_order' => 1,
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => [
                'text' => 'Nested Child',
                '_legacy_section_key' => 'hero',
            ],
        ]);

        $response = $this->get('/admin/content-blocks/service_category/5/export');

        $response->assertOk();

        $payload = $response->json();

        $this->assertSame(2, $payload['schema_version']);
        $this->assertSame('service_category', $payload['page_type']);
        $this->assertCount(1, $payload['blocks']);
        $this->assertArrayNotHasKey('id', $payload['blocks'][0]);
        $this->assertArrayNotHasKey('_legacy_content_block_id', $payload['blocks'][0]['content']);
        $this->assertArrayNotHasKey('id', $payload['blocks'][0]['children'][0]);
        $this->assertArrayNotHasKey('_legacy_section_key', $payload['blocks'][0]['children'][0]['content']);
    }

    public function test_import_creates_fresh_block_ids_and_rebuilds_child_tree_from_portable_json(): void
    {
        PageBlock::create([
            'page_type' => 'service_category',
            'page_id' => 7,
            'block_type' => 'paragraph',
            'category' => 'content',
            'sort_order' => 1,
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => ['text' => 'Old content'],
        ]);

        $payload = [
            'schema_version' => 2,
            'page_type' => 'service_category',
            'page_id' => 5,
            'blocks' => [
                [
                    'id' => 900,
                    'block_type' => 'two_column',
                    'category' => 'layout',
                    'content' => ['ratio' => '1:1'],
                    'children' => [
                        [
                            'id' => 901,
                            'block_type' => 'heading',
                            'category' => 'content',
                            'content' => ['text' => 'Imported Left', '_layout_slot' => 'left'],
                        ],
                        [
                            'id' => 902,
                            'block_type' => 'heading',
                            'category' => 'content',
                            'content' => ['text' => 'Imported Right', '_layout_slot' => 'right'],
                        ],
                    ],
                ],
            ],
        ];

        $file = UploadedFile::fake()->createWithContent('builder.json', json_encode($payload, JSON_THROW_ON_ERROR));

        $response = $this->post('/admin/content-blocks/service_category/7/import', [
            'file' => $file,
        ]);

        $response->assertOk()
            ->assertJson(['success' => true]);

        $blocks = PageBlock::forPage('service_category', 7)->orderBy('sort_order')->get();
        $parent = $blocks->firstWhere('parent_id', null);
        $children = $blocks->where('parent_id', $parent?->id)->values();

        $this->assertCount(3, $blocks);
        $this->assertNotNull($parent);
        $this->assertCount(2, $children);
        $this->assertSame('1:1', $parent->content['ratio']);
        $this->assertSame('Imported Left', $children[0]->content['text']);
        $this->assertSame('Imported Right', $children[1]->content['text']);
        $this->assertNotSame(900, $parent->id);
        $this->assertNotSame(901, $children[0]->id);
        $this->assertNotSame(902, $children[1]->id);
        $this->assertSame($parent->id, $children[0]->parent_id);
        $this->assertSame($parent->id, $children[1]->parent_id);
    }

    public function test_import_rejects_mismatched_page_type_payloads(): void
    {
        $payload = [
            'schema_version' => 2,
            'page_type' => 'blog_index',
            'blocks' => [
                [
                    'block_type' => 'hero',
                    'category' => 'data',
                    'content' => ['heading' => 'Wrong Page Type'],
                ],
            ],
        ];

        $file = UploadedFile::fake()->createWithContent('builder.json', json_encode($payload, JSON_THROW_ON_ERROR));

        $response = $this->post('/admin/content-blocks/service_category/7/import', [
            'file' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertStringContainsString('cannot be imported', $response->json('message'));
    }

    protected function tearDown(): void
    {
        Schema::dropAllTables();

        parent::tearDown();
    }

    private function createTables(): void
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
    }
}
