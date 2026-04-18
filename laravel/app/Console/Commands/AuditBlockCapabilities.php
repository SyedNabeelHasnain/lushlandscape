<?php

namespace App\Console\Commands;

use App\Services\BlockCapabilityAuditService;
use Illuminate\Console\Command;

class AuditBlockCapabilities extends Command
{
    protected $signature = 'blocks:audit-capabilities
                            {--json : Output the full audit payload as JSON}
                            {--write-report= : Write a markdown report to the given path relative to the Laravel base path}';

    protected $description = 'Audit block registry coverage, editor support, and frontend render parity';

    public function handle(BlockCapabilityAuditService $auditService): int
    {
        $audit = $auditService->audit();
        $reportPath = $this->option('write-report');

        if (is_string($reportPath) && $reportPath !== '') {
            $writtenPath = $auditService->writeMarkdownReport($reportPath, $audit);
            $this->info('Markdown report written to '.$writtenPath.'.');
        }

        if ($this->option('json')) {
            $this->line(json_encode($audit, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return self::SUCCESS;
        }

        $this->info('Block capability audit summary:');
        $this->line(' - Registered block types: '.$audit['summary']['registered_block_types']);
        $this->line(' - Blocks missing render surfaces: '.$audit['summary']['blocks_missing_render_surface']);
        $this->line(' - Unsupported content fields: '.$audit['summary']['unsupported_content_fields']);
        $this->line(' - Declared style fields: '.$audit['summary']['declared_style_fields']);
        $this->line(' - Style fields not rendered by shared renderer: '.$audit['summary']['style_fields_not_rendered']);

        $this->renderList('Blocks missing render surfaces', $audit['blocks_missing_render_surface']);
        $this->renderList('Style keys saved but not rendered by the shared renderer', $audit['style_keys_not_rendered']);

        return self::SUCCESS;
    }

    private function renderList(string $title, array $items): void
    {
        if ($items === []) {
            return;
        }

        $this->newLine();
        $this->warn($title.':');

        foreach ($items as $item) {
            $this->line(' - '.$item);
        }
    }
}
