<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Console\Services\ThemeLayoutBlueprintService;
use Illuminate\Console\Command;

class ScaffoldThemeLayouts extends Command
{
    protected $signature = 'app:scaffold-theme-layouts
        {--activate : Activate the generated header and footer layouts immediately}';

    protected $description = 'Create or refresh the master header and footer theme layouts as editable builder drafts.';

    public function handle(ThemeLayoutBlueprintService $blueprints): int
    {
        $activate = (bool) $this->option('activate');
        $layouts = $blueprints->scaffold($activate);

        $this->info('Theme layout scaffolding complete.');
        $this->line(sprintf(
            ' - Header: %s [%s]',
            $layouts['header']->name,
            $layouts['header']->is_active ? 'active' : 'draft',
        ));
        $this->line(sprintf(
            ' - Footer: %s [%s]',
            $layouts['footer']->name,
            $layouts['footer']->is_active ? 'active' : 'draft',
        ));

        if (! $activate) {
            $this->comment('Layouts were scaffolded as drafts so nothing active was replaced automatically.');
            $this->comment('Review them in Admin > Theme Layouts, then activate when ready.');
        }

        return self::SUCCESS;
    }
}
