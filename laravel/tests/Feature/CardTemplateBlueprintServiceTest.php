<?php

namespace Tests\Feature;

use App\Console\Services\CardTemplateBlueprintService;
use App\Models\CardTemplate;
use App\Services\BlockBuilderService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CardTemplateBlueprintServiceTest extends TestCase
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

    public function test_scaffold_creates_core_template_families_as_drafts(): void
    {
        $templates = app(CardTemplateBlueprintService::class)->scaffold();

        $this->assertCount(8, $templates);
        $this->assertCount(8, CardTemplate::all());

        foreach ($templates as $template) {
            $this->assertFalse((bool) $template->is_active);
            $blocks = BlockBuilderService::getUnifiedBlocks('template_card', $template->id);
            $this->assertCount(1, $blocks);
            $this->assertSame('template_card_shell', $blocks->first()['block_type']);
        }
    }

    public function test_scaffold_command_refreshes_templates_without_duplicates_and_can_activate(): void
    {
        app(CardTemplateBlueprintService::class)->scaffold();
        $originalIds = CardTemplate::query()->pluck('id', 'name')->all();

        $exitCode = Artisan::call('app:scaffold-card-templates', ['--activate' => true]);

        $this->assertSame(0, $exitCode, Artisan::output());
        $this->assertCount(8, CardTemplate::all());
        $this->assertSame($originalIds, CardTemplate::query()->pluck('id', 'name')->all());
        $this->assertSame(8, CardTemplate::query()->where('is_active', true)->count());
    }

    protected function tearDown(): void
    {
        Schema::dropAllTables();

        parent::tearDown();
    }

    private function createBuilderTables(): void
    {
        Schema::create('card_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(false);
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
    }
}
