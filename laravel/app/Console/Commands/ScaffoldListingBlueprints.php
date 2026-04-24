<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Services\ListingPageBlueprintService;
use Illuminate\Console\Command;

class ScaffoldListingBlueprints extends Command
{
    protected $signature = 'app:scaffold-listing-blueprints
        {--replace : Replace the current builder content for supported listing and taxonomy pages}';

    protected $description = 'Create the block-first blueprints for hub, index, and supported taxonomy listing pages';

    public function handle(ListingPageBlueprintService $blueprints): int
    {
        $replace = (bool) $this->option('replace');
        $results = $blueprints->scaffold($replace);

        $this->info('Listing blueprint scaffolding complete.');

        $this->line('Singleton pages:');
        foreach ($results['singleton_pages'] as $key => $result) {
            $status = $result['applied'] ? 'scaffolded' : 'skipped';
            $this->line(sprintf(
                ' - %s: %s (%d blocks%s)',
                $key,
                $status,
                $result['block_count'],
                $result['applied'] ? '' : '; existing blocks: '.$result['existing_blocks']
            ));
        }

        $this->line('Taxonomy pages:');
        foreach (['service_categories', 'portfolio_categories', 'blog_categories'] as $group) {
            $records = $results['taxonomy_pages'][$group] ?? [];
            $applied = collect($records)->where('applied', true)->count();
            $skipped = count($records) - $applied;
            $this->line(sprintf(' - %s: %d scaffolded, %d skipped', $group, $applied, $skipped));
        }

        if (! $replace) {
            $this->comment('Existing builder content was preserved. Re-run with --replace when you are ready to intentionally refresh those pages.');
        }

        return self::SUCCESS;
    }
}
