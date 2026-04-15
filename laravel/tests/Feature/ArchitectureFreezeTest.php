<?php

namespace Tests\Feature;

use App\Services\BlockBuilderService;
use App\Services\ContentBlockService;
use Tests\TestCase;

class ArchitectureFreezeTest extends TestCase
{
    public function test_legacy_content_block_registry_has_no_unapproved_additions(): void
    {
        $blocks = require base_path('config/blocks.php');
        $contentBlocks = require base_path('config/content_blocks.php');

        $unifiedTypes = array_keys($blocks['types'] ?? []);
        $legacyTypes = array_keys($contentBlocks['types'] ?? []);

        $legacyOnly = array_values(array_diff($legacyTypes, $unifiedTypes));
        sort($legacyOnly);

        $approvedLegacyOnly = [
            'badge_row',
            'benefits_grid',
            'carousel',
            'comparison_table',
            'container',
            'embed_code',
            'hero_banner',
            'list',
            'local_intro',
            'logo_grid',
            'map_embed',
            'marquee',
            'notice_bar',
            'portfolio_preview',
            'pricing_table',
            'progress_bars',
            'project_showcase',
            'rating_display',
            'seasonal_info',
            'service_area',
            'service_body',
            'service_hero',
            'service_highlight',
            'stats_row',
            'table',
            'team_member',
            'video_embed',
        ];
        sort($approvedLegacyOnly);

        $this->assertSame($approvedLegacyOnly, $legacyOnly);
    }

    public function test_legacy_authoring_path_is_denied_via_content_block_service(): void
    {
        $this->expectException(\RuntimeException::class);

        ContentBlockService::saveBlocks('static_page', 1, [
            [
                'block_type' => 'heading',
                'content' => ['text' => 'Hello'],
            ],
        ]);
    }

    public function test_legacy_authoring_path_is_denied_via_block_builder_legacy_shim(): void
    {
        $this->expectException(\RuntimeException::class);

        BlockBuilderService::saveLegacyBlocks('static_page', 1, [], []);
    }
}
