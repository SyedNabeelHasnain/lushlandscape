<?php

namespace Tests\Feature;

use App\Services\BlockBuilderService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LegacyCleanupTest extends TestCase
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

    public function test_sync_legacy_blocks_merges_missing_legacy_blocks_into_existing_page(): void
    {
        DB::table('page_blocks')->insert([
            'page_type' => 'home',
            'page_id' => null,
            'block_type' => 'hero',
            'category' => 'data',
            'sort_order' => 1,
            'is_enabled' => 1,
            'show_on_desktop' => 1,
            'show_on_tablet' => 1,
            'show_on_mobile' => 1,
            'content' => json_encode(['heading' => 'Hero']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('page_content_blocks')->insert([
            'page_type' => 'home',
            'page_id' => 0,
            'section_key' => 'home_why_choose',
            'block_type' => 'feature_list',
            'sort_order' => 1,
            'is_enabled' => 1,
            'content' => json_encode([
                'heading' => 'Why Choose Us',
                'features' => [
                    ['title' => 'Craftsmanship', 'description' => 'Built to last.'],
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exitCode = Artisan::call('blocks:sync-legacy', ['--write' => true]);
        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Created 1 unified page_blocks.', $output);
        $this->assertDatabaseCount('page_blocks', 2);

        $legacyBlock = DB::table('page_blocks')
            ->where('page_type', 'home')
            ->where('block_type', 'feature_list')
            ->first();

        $this->assertNotNull($legacyBlock);
        $this->assertSame(2, $legacyBlock->sort_order);
    }

    public function test_prune_legacy_blocks_command_deletes_only_synced_rows(): void
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

        BlockBuilderService::ensureLegacyBackfilled('service', 5);

        $dryRunExitCode = Artisan::call('blocks:prune-legacy');
        $dryRunOutput = Artisan::output();

        $this->assertSame(0, $dryRunExitCode);
        $this->assertStringContainsString('Pages safe to prune: 1', $dryRunOutput);

        $writeExitCode = Artisan::call('blocks:prune-legacy', ['--write' => true]);
        $writeOutput = Artisan::output();

        $this->assertSame(0, $writeExitCode);
        $this->assertStringContainsString('Deleted 1 page_content_blocks rows.', $writeOutput);
        $this->assertDatabaseCount('page_content_blocks', 0);
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
