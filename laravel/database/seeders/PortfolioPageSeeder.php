<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BlockBuilderService;

class PortfolioPageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed the Portfolio Index (Hub)
        $indexBlocks = [
            [
                'block_type' => 'portfolio_masonry_grid_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Our Work',
                    'heading' => 'Completed<br>Environments',
                    'description' => 'A curated selection of luxury landscape constructions across the Greater Toronto Area.',
                ],
            ],
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source_id' => 'consultation',
                'content' => [
                    'eyebrow' => 'Start Your Project',
                    'heading' => 'Request a<br>Consultation',
                    'description' => 'Ready to transform your property? Share your architectural vision and our concierge will contact you.',
                ],
            ]
        ];

        BlockBuilderService::saveUnifiedBlocks('portfolio_index', 0, $indexBlocks);
        BlockBuilderService::saveUnifiedBlocks('portfolio_category', 0, $indexBlocks);

        // 2. Seed the Default Portfolio Project Layout
        // Note: For dynamic templates (like portfolio_project), page_id = 0 acts as the global default fallback.
        $projectBlocks = [
            [
                'block_type' => 'portfolio_project_hero_luxury',
                'is_enabled' => true,
                'content' => [],
            ],
            [
                'block_type' => 'project_spec_sheet_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Project Overview',
                ],
            ],
            [
                'block_type' => 'before_after_slider_luxury',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Site Transformation',
                    'description' => 'Drag the slider to reveal the structural correction and design integration.',
                ],
            ],
            [
                'block_type' => 'portfolio_gallery_masonry_luxury',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Visual Documentation',
                ],
            ],
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source_id' => 'consultation',
                'content' => [
                    'eyebrow' => 'Inspired by this project?',
                    'heading' => 'Request a<br>Consultation',
                    'description' => 'Share your architectural vision. Our project concierge will review your requirements.',
                ],
            ]
        ];

        BlockBuilderService::saveUnifiedBlocks('portfolio_project', 0, $projectBlocks);
    }
}