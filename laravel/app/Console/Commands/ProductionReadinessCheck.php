<?php

namespace App\Console\Commands;

use App\Services\BlockBuilderService;
use App\Services\LegacyGovernanceService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProductionReadinessCheck extends Command
{
    protected $signature = 'app:readiness-check
                            {--strict : Treat warnings as failures}
                            {--target=production : Readiness target: production or staging}';

    protected $description = 'Verify the application is configured for production';

    public function handle(): int
    {
        $issues = [];
        $warnings = [];
        $notes = [];
        $target = strtolower((string) $this->option('target'));

        if (! in_array($target, ['production', 'staging'], true)) {
            $this->error("Unsupported readiness target '{$target}'. Use production or staging.");

            return self::FAILURE;
        }

        $appUrlRaw = (string) config('app.url');
        $appUrl = rtrim(trim(str_replace('`', '', $appUrlRaw)), '.');
        $appEnv = (string) config('app.env');
        $appDebug = (bool) config('app.debug');
        $sessionSecure = (bool) config('session.secure');
        $databaseConnection = (string) config('database.default');
        $databaseHost = (string) data_get(config("database.connections.{$databaseConnection}"), 'host', '');

        if ($appEnv !== 'production') {
            $issues[] = "APP_ENV must be 'production' (current: {$appEnv}).";
        } else {
            $notes[] = 'APP_ENV is production.';
        }

        if ($appDebug) {
            $issues[] = 'APP_DEBUG must be false in production.';
        } else {
            $notes[] = 'APP_DEBUG is disabled.';
        }

        if ($appUrl === '') {
            $issues[] = 'APP_URL is empty.';
        } else {
            $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: '';
            $host = strtolower((string) (parse_url($appUrl, PHP_URL_HOST) ?: ''));

            if ($scheme !== 'https') {
                $issues[] = "APP_URL must use https (current: {$appUrlRaw}).";
            } elseif ($host === '') {
                $issues[] = "APP_URL host is invalid (current: {$appUrlRaw}).";
            } else {
                $notes[] = "APP_URL is set to {$appUrl}.";
            }

            if (in_array($host, ['localhost', '127.0.0.1'], true) || str_contains($host, '.test')) {
                $issues[] = "APP_URL points to a local development host ({$host}).";
            }

            if ($target === 'production' && (str_contains($host, 'staging') || str_contains($host, 'test'))) {
                $issues[] = "APP_URL still points to a staging host ({$host}).";
            }

            if ($target === 'staging' && ! str_contains($host, 'staging') && ! str_contains($host, 'test')) {
                $issues[] = "APP_URL must point to a staging host for a staging readiness check (current host: {$host}).";
            }
        }

        if (! $sessionSecure) {
            $issues[] = 'Session cookies must be marked secure.';
        } else {
            $notes[] = 'Session cookies are secure.';
        }

        if ($databaseConnection === 'mysql' && in_array($databaseHost, ['127.0.0.1', 'localhost'], true)) {
            $warnings[] = "MySQL host is loopback ({$databaseHost}). This is acceptable when the database runs on the same host.";
        } else {
            $notes[] = "Database connection '{$databaseConnection}' is configured.";
        }

        if (! app()->configurationIsCached()) {
            $warnings[] = 'Configuration cache is not built.';
        } else {
            $notes[] = 'Configuration cache is built.';
        }

        if (method_exists(app(), 'routesAreCached') && ! app()->routesAreCached()) {
            $warnings[] = 'Route cache is not built.';
        } else {
            $notes[] = 'Route cache is built.';
        }

        if (method_exists(app(), 'eventsAreCached') && ! app()->eventsAreCached()) {
            $warnings[] = 'Event cache is not built.';
        } else {
            $notes[] = 'Event cache is built.';
        }

        try {
            DB::scalar('select 1');
            $notes[] = 'Database connection succeeded.';
        } catch (\Throwable $exception) {
            $issues[] = 'Database readiness check failed: '.$exception->getMessage();
        }

        if (! LegacyGovernanceService::strictEnabled()) {
            $warnings[] = 'LEGACY_STRICT is disabled. Strict mode should remain enabled for release readiness.';
        } else {
            $notes[] = 'LEGACY_STRICT is enabled.';
        }

        }

        $this->renderSection('Passes', $notes, 'info');
        $this->renderSection('Warnings', $warnings, 'warn');
        $this->renderSection('Blockers', $issues, 'error');

        if ($issues === [] && $warnings === []) {
            $this->newLine();
            $this->info('No blocking issues found. '.$this->targetLabel($target).' readiness checks passed.');

            return self::SUCCESS;
        }

        if ($issues === [] && $this->option('strict')) {
            $this->newLine();
            $this->error('Warnings were treated as failures because --strict was used.');

            return self::FAILURE;
        }

        if ($issues !== []) {
            $this->newLine();
            $this->error($this->targetLabel($target).' readiness checks failed.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->comment('No blockers found, but warnings remain.');

        return self::SUCCESS;
    }

    private function renderSection(string $title, array $items, string $style): void
    {
        if ($items === []) {
            return;
        }

        $this->newLine();
        $this->{$style}($title.':');

        foreach ($items as $item) {
            $this->line(' - '.$item);
        }
    }

    private function targetLabel(string $target): string
    {
        return ucfirst($target);
    }
}
