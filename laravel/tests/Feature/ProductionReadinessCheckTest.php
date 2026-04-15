<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductionReadinessCheckTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        putenv('LEGACY_STRICT=true');
        $_ENV['LEGACY_STRICT'] = 'true';

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        $this->createBlockTables();
    }

    public function test_readiness_check_passes_for_a_hardened_runtime(): void
    {
        config([
            'app.env' => 'production',
            'app.debug' => false,
            'app.url' => 'https://lushlandscape.ca',
            'session.secure' => true,
        ]);

        $exitCode = Artisan::call('app:readiness-check');
        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('APP_ENV is production.', $output);
        $this->assertStringNotContainsString('Blockers:', $output);
    }

    public function test_readiness_check_passes_for_a_hardened_staging_runtime_when_requested(): void
    {
        config([
            'app.env' => 'production',
            'app.debug' => false,
            'app.url' => 'https://staging.lushlandscape.ca',
            'session.secure' => true,
        ]);

        $exitCode = Artisan::call('app:readiness-check', ['--target' => 'staging']);
        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('APP_URL is set to https://staging.lushlandscape.ca.', $output);
        $this->assertStringNotContainsString('Blockers:', $output);
    }

    public function test_readiness_check_fails_for_local_debug_runtime(): void
    {
        config([
            'app.env' => 'local',
            'app.debug' => true,
            'app.url' => 'https://staging.lushlandscape.ca',
            'session.secure' => false,
        ]);

        $exitCode = Artisan::call('app:readiness-check');
        $output = Artisan::output();

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString("APP_ENV must be 'production'", $output);
        $this->assertStringContainsString('APP_DEBUG must be false in production.', $output);
        $this->assertStringContainsString('APP_URL still points to a staging host', $output);
        $this->assertStringContainsString('Session cookies must be marked secure.', $output);
    }

    public function test_readiness_check_fails_when_legacy_pages_have_partial_unified_parity(): void
    {
        config([
            'app.env' => 'production',
            'app.debug' => false,
            'app.url' => 'https://lushlandscape.ca',
            'session.secure' => true,
        ]);

        DB::table('page_blocks')->insert([
            'page_type' => 'home',
            'page_id' => null,
            'block_type' => 'hero',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('page_content_blocks')->insert([
            'page_type' => 'home',
            'page_id' => 0,
            'block_type' => 'feature_list',
            'content' => json_encode([
                'heading' => 'Why Choose Us',
                'features' => [
                    ['title' => 'Craftsmanship'],
                ],
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exitCode = Artisan::call('app:readiness-check');
        $output = Artisan::output();

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('Unified page_blocks are still missing for 1 legacy pages.', $output);
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
            $table->string('block_type')->nullable();
            $table->timestamps();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page_type');
            $table->unsignedBigInteger('page_id')->nullable();
            $table->string('section_key')->nullable();
            $table->timestamps();
        });

        Schema::create('page_content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('page_type');
            $table->unsignedBigInteger('page_id')->default(0);
            $table->string('block_type')->nullable();
            $table->json('content')->nullable();
            $table->timestamps();
        });
    }
}
