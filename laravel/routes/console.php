<?php

use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->daily();

Artisan::command('blocks:sync-legacy {--write}', function () {
    $legacyPages = BlockBuilderService::legacyPageInventory();
    $this->line('Legacy pages detected: '.$legacyPages->count());

    $missingPages = $legacyPages->filter(function (array $page) {
        return BlockBuilderService::missingLegacyBlockCount($page['page_type'], $page['page_id']) > 0;
    })->values();

    $this->line('Pages still missing unified page_blocks: '.$missingPages->count());

    if (! $this->option('write')) {
        return 0;
    }

    $syncedPages = 0;
    $createdBlocks = 0;
    foreach ($missingPages as $page) {
        $syncedPages++;
        $createdBlocks += BlockBuilderService::ensureLegacyBackfilled($page['page_type'], $page['page_id']);
    }

    $this->line("Synced {$syncedPages} legacy pages.");
    $this->line("Created {$createdBlocks} unified page_blocks.");

    return 0;
});

Artisan::command('blocks:prune-legacy {--write}', function () {
    $legacyPages = BlockBuilderService::legacyPageInventory();

    $safePages = $legacyPages->filter(function (array $page) {
        $counts = BlockBuilderService::legacyRowCounts($page['page_type'], $page['page_id']);
        if (($counts['page_sections'] ?? 0) === 0 && ($counts['page_content_blocks'] ?? 0) === 0) {
            return false;
        }

        return BlockBuilderService::missingLegacyBlockCount($page['page_type'], $page['page_id']) === 0;
    })->values();

    $this->line('Pages safe to prune: '.$safePages->count());

    if (! $this->option('write')) {
        return 0;
    }

    $deletedContent = 0;
    $deletedSections = 0;
    foreach ($safePages as $page) {
        $result = BlockBuilderService::pruneLegacyData($page['page_type'], $page['page_id']);
        $deletedContent += (int) ($result['page_content_blocks'] ?? 0);
        $deletedSections += (int) ($result['page_sections'] ?? 0);
    }

    $this->line("Deleted {$deletedContent} page_content_blocks rows.");
    if ($deletedSections > 0) {
        $this->line("Deleted {$deletedSections} page_sections rows.");
    }

    return 0;
});
