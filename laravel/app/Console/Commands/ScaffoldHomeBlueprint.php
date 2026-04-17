<?php

namespace App\Console\Commands;

use App\Console\Services\HomePageBlueprintService;
use Illuminate\Console\Command;

class ScaffoldHomeBlueprint extends Command
{
    protected $signature = 'blocks:scaffold-home {--force : Overwrite existing blocks}';

    protected $description = 'Scaffolds the Home Page blocks via the Blueprint Service';

    public function handle(HomePageBlueprintService $service)
    {
        $this->info('Scaffolding Home Page blueprint...');
        $result = $service->scaffold($this->option('force'));

        if ($result['applied']) {
            $this->info("Successfully built Home Page. Blocks inserted: {$result['block_count']}.");
        } else {
            $this->warn('Home Page not scaffolded because blocks already exist. Use --force to overwrite.');
        }

        return Command::SUCCESS;
    }
}
