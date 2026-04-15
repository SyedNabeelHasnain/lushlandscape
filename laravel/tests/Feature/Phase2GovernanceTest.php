<?php

namespace Tests\Feature;

use App\Services\BlockGovernanceService;
use Tests\TestCase;

class Phase2GovernanceTest extends TestCase
{
    public function test_theme_blocks_are_denied_outside_theme_layout(): void
    {
        $this->expectException(\RuntimeException::class);

        BlockGovernanceService::validateBlocksForPage('home', [
            [
                'block_type' => 'theme_header_shell',
                'is_enabled' => true,
                'content' => [],
                'children' => [],
            ],
        ]);
    }

    public function test_hero_requires_heading_when_enabled(): void
    {
        $this->expectException(\RuntimeException::class);

        BlockGovernanceService::validateBlocksForPage('home', [
            [
                'block_type' => 'hero',
                'is_enabled' => true,
                'content' => [
                    'heading' => '',
                ],
            ],
        ]);
    }

    public function test_hero_allows_missing_heading_when_disabled(): void
    {
        BlockGovernanceService::validateBlocksForPage('home', [
            [
                'block_type' => 'hero',
                'is_enabled' => false,
                'content' => [
                    'heading' => '',
                ],
            ],
        ]);

        $this->assertTrue(true);
    }

    public function test_theme_header_shell_requires_layout_slots_for_children(): void
    {
        $this->expectException(\RuntimeException::class);

        BlockGovernanceService::validateBlocksForPage('theme_layout', [
            [
                'block_type' => 'theme_header_shell',
                'is_enabled' => true,
                'content' => [
                    'mode' => 'glass',
                    'tone' => 'dark',
                ],
                'children' => [
                    [
                        'block_type' => 'site_logo',
                        'is_enabled' => true,
                        'content' => [],
                    ],
                ],
            ],
        ]);
    }

    public function test_cta_section_rejects_invalid_variant(): void
    {
        $this->expectException(\RuntimeException::class);

        BlockGovernanceService::validateBlocksForPage('home', [
            [
                'block_type' => 'cta_section',
                'is_enabled' => true,
                'content' => [
                    'variant' => 'not-real',
                    'title' => 'CTA',
                    'button_text' => 'Book',
                    'button_url' => '/contact',
                ],
            ],
        ]);
    }
}
