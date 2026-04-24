<?php

declare(strict_types=1);

namespace App\Services;

class CuratedMediaCatalogService
{
    private ?array $dataset = null;

    public function __construct(
        private readonly ?string $catalogPath = null
    ) {}

    public function hasCatalog(): bool
    {
        return is_file($this->path());
    }

    public function loadDataset(): array
    {
        if ($this->dataset !== null) {
            return $this->dataset;
        }

        if (! $this->hasCatalog()) {
            return $this->dataset = $this->emptyDataset();
        }

        $decoded = json_decode((string) file_get_contents($this->path()), true);

        if (! is_array($decoded) || ! isset($decoded['items']) || ! is_array($decoded['items'])) {
            return $this->dataset = $this->emptyDataset();
        }

        return $this->dataset = $decoded;
    }

    public function allItems(): array
    {
        return array_values($this->loadDataset()['items'] ?? []);
    }

    public function matchItem(array $context, array $usedUrls = []): ?array
    {
        $scored = [];

        foreach ($this->allItems() as $item) {
            if (! $this->isEligible($item, $usedUrls)) {
                continue;
            }

            $score = $this->contextScore($item, $context);
            if ($score <= 0) {
                continue;
            }

            $scored[] = ['score' => $score, 'item' => $item];
        }

        if ($scored === []) {
            $query = trim(implode(' ', array_filter([
                $context['internal_title'] ?? null,
                $context['search_query'] ?? null,
                $context['description'] ?? null,
                $context['default_alt_text'] ?? null,
                $context['placement'] ?? null,
                $context['service_slug'] ?? null,
                $context['category_slug'] ?? null,
                $context['city_slug'] ?? null,
                $context['page_slug'] ?? null,
            ])));

            return $query !== '' ? $this->search($query, 'landscape', 0, $usedUrls) : null;
        }

        usort($scored, fn (array $a, array $b) => $b['score'] <=> $a['score']);

        return $this->toFetchResult($scored[0]['item']);
    }

    public function search(string $query, string $orientation = 'landscape', int $minWidth = 0, array $usedUrls = []): ?array
    {
        $terms = $this->normalizeTerms($query);
        if ($terms === []) {
            return null;
        }

        $scored = [];

        foreach ($this->allItems() as $item) {
            if (! $this->isEligible($item, $usedUrls)) {
                continue;
            }

            if (($item['orientation'] ?? 'landscape') !== $orientation && ($item['orientation'] ?? null) !== null) {
                continue;
            }

            if (($item['width'] ?? 0) > 0 && $minWidth > 0 && (int) $item['width'] < $minWidth) {
                continue;
            }

            $haystack = implode(' ', array_filter([
                $item['internal_title'] ?? '',
                $item['description'] ?? '',
                $item['default_alt_text'] ?? '',
                implode(' ', (array) ($item['tags'] ?? [])),
                implode(' ', (array) ($item['keywords'] ?? [])),
                $item['placement'] ?? '',
                $item['service_slug'] ?? '',
                $item['category_slug'] ?? '',
                $item['city_slug'] ?? '',
                $item['page_slug'] ?? '',
            ]));

            $score = 0;
            foreach ($terms as $term) {
                if (str_contains($haystack, $term)) {
                    $score += 10;
                }
            }

            if ($score > 0) {
                $scored[] = ['score' => $score, 'item' => $item];
            }
        }

        if ($scored === []) {
            return null;
        }

        usort($scored, fn (array $a, array $b) => $b['score'] <=> $a['score']);

        return $this->toFetchResult($scored[0]['item']);
    }

    private function isEligible(array $item, array $usedUrls): bool
    {
        $url = trim((string) ($item['url'] ?? ''));

        return $url !== '' && ! in_array($url, $usedUrls, true);
    }

    private function contextScore(array $item, array $context): int
    {
        $score = 0;
        $placement = $context['placement'] ?? null;

        if ($placement !== null) {
            if (($item['placement'] ?? null) !== $placement) {
                return 0;
            }

            $score += 50;
        }

        foreach (['service_slug', 'category_slug', 'city_slug', 'page_slug'] as $key) {
            $expected = $context[$key] ?? null;
            $actual = $item[$key] ?? null;

            if ($expected === null || $expected === '') {
                continue;
            }

            if ($expected === $actual) {
                $score += 25;
            } elseif ($actual !== null && $actual !== '') {
                return 0;
            }
        }

        if (($context['location_city'] ?? null) !== null && ($item['location_city'] ?? null) === ($context['location_city'] ?? null)) {
            $score += 10;
        }

        return $score;
    }

    private function normalizeTerms(string $query): array
    {
        $parts = preg_split('/[^a-z0-9]+/i', strtolower($query)) ?: [];

        return array_values(array_unique(array_filter($parts, fn (string $part) => strlen($part) >= 3)));
    }

    private function toFetchResult(array $item): array
    {
        return [
            'url' => $item['url'],
            'credit' => $item['credit'] ?? null,
            'width' => $item['width'] ?? null,
            'height' => $item['height'] ?? null,
            'source_page' => $item['source_page'] ?? null,
        ];
    }

    private function emptyDataset(): array
    {
        return [
            'generated_at' => now()->toIso8601String(),
            'strategy' => 'official_catalog',
            'total' => 0,
            'summary' => [],
            'items' => [],
        ];
    }

    private function path(): string
    {
        return $this->catalogPath ?? config('services.official_media.catalog_path', storage_path('app/media-curated-catalog.json'));
    }
}
