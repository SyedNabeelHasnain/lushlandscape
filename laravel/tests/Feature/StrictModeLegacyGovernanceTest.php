<?php

namespace Tests\Feature;

use App\Services\LegacyGovernanceService;
use Tests\TestCase;

class StrictModeLegacyGovernanceTest extends TestCase
{
    public function test_legacy_read_throws_for_strict_page_types_when_strict_enabled(): void
    {
        putenv('LEGACY_STRICT=true');
        $_ENV['LEGACY_STRICT'] = 'true';

        putenv('APP_RUNNING_IN_CONSOLE=false');
        $_ENV['APP_RUNNING_IN_CONSOLE'] = 'false';
        $this->resetRunningInConsoleCache();

        config(['blocks.strict_unified_page_types' => ['home']]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Legacy presentation read is not allowed in strict mode for 'home'.");

        LegacyGovernanceService::legacyRead('test', 'home', 0);
    }

    public function test_legacy_read_does_not_throw_for_non_strict_page_types_when_strict_enabled(): void
    {
        putenv('LEGACY_STRICT=true');
        $_ENV['LEGACY_STRICT'] = 'true';

        putenv('APP_RUNNING_IN_CONSOLE=false');
        $_ENV['APP_RUNNING_IN_CONSOLE'] = 'false';
        $this->resetRunningInConsoleCache();

        config(['blocks.strict_unified_page_types' => ['home']]);

        LegacyGovernanceService::legacyRead('test', 'blog_post', 1);

        $this->assertTrue(true);
    }

    private function resetRunningInConsoleCache(): void
    {
        $ref = new \ReflectionClass($this->app);
        if (! $ref->hasProperty('isRunningInConsole')) {
            return;
        }

        $prop = $ref->getProperty('isRunningInConsole');
        $prop->setAccessible(true);
        $prop->setValue($this->app, null);
    }
}
