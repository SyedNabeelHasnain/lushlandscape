<?php

namespace Tests\Feature;

use App\Services\BlockBuilderService;
use App\Services\ContentBlockService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LegacyBlockSyncTest extends TestCase
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

    public function test_content_block_service_writes_to_unified_page_blocks(): void
    {
        DB::table('page_blocks')->insert([
            'page_type' => 'static_page',
            'page_id' => 7,
            'block_type' => 'rich_text',
            'category' => 'content',
            'sort_order' => 1,
            'is_enabled' => 1,
            'show_on_desktop' => 1,
            'show_on_tablet' => 1,
            'show_on_mobile' => 1,
            'content' => json_encode([
                'html' => '<p>About</p>',
                '_legacy_section_key' => 'about_story',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertDatabaseCount('page_blocks', 1);

        $legacyBlock = ContentBlockService::getAllBlocks('static_page', 7)->first();

        $this->assertSame('about_story', $legacyBlock->section_key);
        $this->assertSame('rich_text', $legacyBlock->block_type);
    }

    public function test_block_builder_requires_explicit_sync_for_legacy_content_blocks(): void
    {
        DB::table('page_content_blocks')->insert([
            'page_type' => 'service',
            'page_id' => 5,
            'section_key' => 'main',
            'block_type' => 'rich_text',
            'sort_order' => 1,
            'is_enabled' => 1,
            'content' => json_encode(['html' => '<p>Legacy block</p>']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $blocks = BlockBuilderService::getUnifiedBlocks('service', 5);

        $this->assertCount(0, $blocks);
        $this->assertDatabaseCount('page_blocks', 0);

        $created = BlockBuilderService::ensureLegacyBackfilled('service', 5);
        $blocks = BlockBuilderService::getUnifiedBlocks('service', 5);

        $this->assertSame(1, $created);
        $this->assertCount(1, $blocks);
        $this->assertDatabaseCount('page_blocks', 1);
        $this->assertSame('rich_text', $blocks->first()['block_type']);
        $this->assertSame('<p>Legacy block</p>', $blocks->first()['content']['html']);
    }

    public function test_sync_legacy_blocks_command_reports_and_materializes_missing_pages(): void
    {
        DB::table('page_content_blocks')->insert([
            [
                'page_type' => 'city',
                'page_id' => 1,
                'section_key' => 'main',
                'block_type' => 'rich_text',
                'sort_order' => 1,
                'is_enabled' => 1,
                'content' => json_encode(['html' => '<p>City One</p>']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'page_type' => 'city',
                'page_id' => 2,
                'section_key' => 'main',
                'block_type' => 'rich_text',
                'sort_order' => 1,
                'is_enabled' => 1,
                'content' => json_encode(['html' => '<p>City Two</p>']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $dryRunExitCode = Artisan::call('blocks:sync-legacy');
        $dryRunOutput = Artisan::output();
        $this->assertSame(0, $dryRunExitCode);
        $this->assertStringContainsString('Legacy pages detected: 2', $dryRunOutput);
        $this->assertStringContainsString('Pages still missing unified page_blocks: 2', $dryRunOutput);

        $writeExitCode = Artisan::call('blocks:sync-legacy', ['--write' => true]);
        $writeOutput = Artisan::output();
        $this->assertSame(0, $writeExitCode);
        $this->assertStringContainsString('Synced 2 legacy pages.', $writeOutput);
        $this->assertStringContainsString('Created 2 unified page_blocks.', $writeOutput);

        $this->assertDatabaseCount('page_blocks', 2);
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
