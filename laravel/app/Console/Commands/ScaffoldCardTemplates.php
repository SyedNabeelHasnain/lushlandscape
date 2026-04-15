<?php

namespace App\Console\Commands;

use App\Console\Services\CardTemplateBlueprintService;
use Illuminate\Console\Command;

class ScaffoldCardTemplates extends Command
{
    protected $signature = 'app:scaffold-card-templates
        {--activate : Activate the generated card templates immediately}';

    protected $description = 'Create or refresh the core card template families as editable builder drafts.';

    public function handle(CardTemplateBlueprintService $blueprints): int
    {
        $activate = (bool) $this->option('activate');
        $templates = $blueprints->scaffold($activate);

        $this->info('Card template scaffolding complete.');

        foreach ($templates as $template) {
            $this->line(sprintf(
                ' - %s [%s]',
                $template->name,
                $template->is_active ? 'active' : 'draft',
            ));
        }

        if (! $activate) {
            $this->comment('Templates were scaffolded as drafts so nothing active was replaced automatically.');
            $this->comment('Review them in Admin > Card Templates, then activate when ready.');
        }

        return self::SUCCESS;
    }
}

