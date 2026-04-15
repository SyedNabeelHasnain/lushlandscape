<?php

namespace Tests\Feature;

use App\Services\CuratedMediaCatalogService;
use Tests\TestCase;

class CuratedMediaCatalogServiceTest extends TestCase
{
    public function test_it_matches_an_exact_context_before_falling_back_to_keyword_search(): void
    {
        $path = storage_path('framework/testing/curated-media-catalog-test.json');

        file_put_contents($path, json_encode([
            'generated_at' => now()->toIso8601String(),
            'total' => 2,
            'summary' => [
                'service_hero' => 1,
                'service_gallery' => 1,
            ],
            'items' => [
                [
                    'url' => 'https://example.com/driveway-hero.jpg',
                    'credit' => 'Image from Unilock',
                    'placement' => 'service_hero',
                    'service_slug' => 'interlocking-driveways',
                    'internal_title' => 'Interlocking Driveways - Hero',
                    'description' => 'Curated hero image for Interlocking Driveways.',
                    'default_alt_text' => 'Driveway hero',
                    'tags' => ['interlocking-driveways', 'hero'],
                    'keywords' => ['interlocking driveways', 'hero'],
                    'orientation' => 'landscape',
                ],
                [
                    'url' => 'https://example.com/patio-gallery.jpg',
                    'credit' => 'Image from Techo-Bloc',
                    'placement' => 'service_gallery',
                    'service_slug' => 'interlocking-patios-backyard-living',
                    'internal_title' => 'Interlocking Patios & Backyard Living - Gallery 1',
                    'description' => 'Curated gallery image for patios.',
                    'default_alt_text' => 'Patio gallery',
                    'tags' => ['interlocking-patios-backyard-living', 'gallery'],
                    'keywords' => ['patio', 'gallery'],
                    'orientation' => 'landscape',
                ],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $service = new CuratedMediaCatalogService($path);

        $match = $service->matchItem([
            'placement' => 'service_hero',
            'service_slug' => 'interlocking-driveways',
        ]);

        $search = $service->search('patio gallery image');

        $this->assertSame('https://example.com/driveway-hero.jpg', $match['url']);
        $this->assertSame('https://example.com/patio-gallery.jpg', $search['url']);

        @unlink($path);
    }
}
