<?php

declare(strict_types=1);

namespace App\Services;

class MediaAcquisitionService
{
    public function __construct(
        private readonly ?CuratedMediaCatalogService $catalog = null
    ) {}

    public function generateDataset(): array
    {
        return $this->catalog()->loadDataset();
    }

    public function fetchImageForItem(array $item, array $usedUrls = []): ?array
    {
        return $this->catalog()->matchItem($item, $usedUrls);
    }

    public function fetchImage(string $query, string $orientation = 'landscape', int $minWidth = 1200, array $usedUrls = []): ?array
    {
        return $this->catalog()->search($query, $orientation, $minWidth, $usedUrls);
    }

    private function catalog(): CuratedMediaCatalogService
    {
        return $this->catalog ?? new CuratedMediaCatalogService;
    }
}
