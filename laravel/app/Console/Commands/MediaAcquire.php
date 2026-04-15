<?php

namespace App\Console\Commands;

use App\Services\MediaAcquisitionService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MediaAcquire extends Command
{
    protected $signature = 'media:acquire
        {--generate-dataset : Generate the dataset JSON without fetching images}
        {--fetch : Backfill any missing URLs from the curated official media catalog}
        {--output=storage/app/media-dataset.json : Output file path}
        {--limit=0 : Limit number of items to process (0 = all)}
        {--offset=0 : Skip this many items before processing}
        {--placement= : Filter by placement type (service_hero, service_gallery, city_hero, etc.)}
        {--dry-run : Show what would be fetched without making API calls}';

    protected $description = 'Generate and populate the curated media dataset for bulk import';

    public function handle(): int
    {
        $service = new MediaAcquisitionService;

        if ($this->option('generate-dataset') || ! $this->option('fetch')) {
            return $this->generateDataset($service);
        }

        return $this->fetchUrls($service);
    }

    private function generateDataset(MediaAcquisitionService $service): int
    {
        $this->info('Generating media dataset...');

        $dataset = $service->generateDataset();
        $outputPath = base_path($this->option('output'));

        file_put_contents($outputPath, json_encode($dataset, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->newLine();
        $this->info('Dataset generated successfully!');
        $this->table(
            ['Placement Type', 'Count'],
            collect($dataset['summary'])->map(fn ($count, $key) => [str_replace('_', ' ', ucfirst($key)), $count])->values()->toArray()
        );
        $this->info("Total items: {$dataset['total']}");
        $this->info("Saved to: {$outputPath}");

        return Command::SUCCESS;
    }

    private function fetchUrls(MediaAcquisitionService $service): int
    {
        $outputPath = base_path($this->option('output'));

        if (! file_exists($outputPath)) {
            $this->warn('Dataset file not found. Generating first...');
            $dataset = $service->generateDataset();
        } else {
            $dataset = json_decode(file_get_contents($outputPath), true);
            if (! $dataset || ! isset($dataset['items'])) {
                $this->error('Invalid dataset file.');

                return Command::FAILURE;
            }
        }

        $items = $dataset['items'];
        $placement = $this->option('placement');
        $limit = (int) $this->option('limit');
        $offset = (int) $this->option('offset');
        $dryRun = $this->option('dry-run');

        // Filter by placement if specified
        if ($placement) {
            $items = array_values(array_filter($items, fn ($item) => ($item['placement'] ?? '') === $placement));
            $this->info("Filtered to {$placement}: ".count($items).' items');
        }

        // Apply offset and limit
        if ($offset > 0) {
            $items = array_slice($items, $offset);
        }
        if ($limit > 0) {
            $items = array_slice($items, 0, $limit);
        }

        $this->info('Processing '.count($items).' items...');

        if ($dryRun) {
            $this->table(
                ['#', 'Title', 'Query', 'Placement'],
                collect($items)->take(20)->map(fn ($item, $i) => [
                    $i + 1,
                    Str::limit($item['internal_title'], 40),
                    Str::limit($item['search_query'] ?? implode(', ', array_slice((array) ($item['keywords'] ?? []), 0, 3)), 40),
                    $item['placement'] ?? '-',
                ])->toArray()
            );
            if (count($items) > 20) {
                $this->info('... and '.(count($items) - 20).' more');
            }

            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($items));
        $bar->start();

        $fetched = 0;
        $failed = 0;
        $usedUrls = []; // Track URLs to avoid duplicates

        // Map items back to dataset indices
        $allItems = $dataset['items'];

        foreach ($items as $item) {
            if (! empty($item['url'])) {
                $bar->advance();

                continue;
            }

            $result = $service->fetchImageForItem($item, $usedUrls);

            if ($result && ! in_array($result['url'], $usedUrls)) {
                // Find this item in the master dataset and update it
                foreach ($allItems as &$masterItem) {
                    if ($masterItem['internal_title'] === $item['internal_title'] && empty($masterItem['url'])) {
                        $masterItem['url'] = $result['url'];
                        $masterItem['credit'] = $result['credit'];
                        break;
                    }
                }
                unset($masterItem);

                $usedUrls[] = $result['url'];
                $fetched++;
            } else {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Save updated dataset
        $dataset['items'] = $allItems;
        $dataset['fetched_at'] = now()->toIso8601String();
        file_put_contents($outputPath, json_encode($dataset, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info("Backfilled: {$fetched} | Unmatched: {$failed}");
        $this->info("Saved to: {$outputPath}");

        return Command::SUCCESS;
    }
}
