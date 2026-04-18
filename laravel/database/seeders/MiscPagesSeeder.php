<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BlockBuilderService;

class MiscPagesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Contact Page
        $contactBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Get in Touch',
                    'heading' => 'Contact Lush Landscape',
                ],
            ],
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source' => [],
                'content' => [
                    'eyebrow' => 'Inquiries',
                    'heading' => 'Send us a<br>Message',
                    'description' => 'Our team is ready to assist you with your luxury landscape and interlocking needs. Reach out to schedule a consultation or ask a question.',
                ],
            ]
        ];
        BlockBuilderService::saveUnifiedBlocks('contact', 0, $contactBlocks);

        // 2. FAQ Index Page
        $faqBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Knowledge Base',
                    'heading' => 'Frequently Asked Questions',
                ],
            ],
            [
                'block_type' => 'local_faq_accordion_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Client Guidelines',
                    'heading' => 'Common Inquiries',
                ],
            ],
        ];
        BlockBuilderService::saveUnifiedBlocks('faq_index', 0, $faqBlocks);

        // 3. Blog Index Page
        $blogBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Journal',
                    'heading' => 'Landscape Insights',
                ],
            ],
        ];
        BlockBuilderService::saveUnifiedBlocks('blog_index', 0, $blogBlocks);
        BlockBuilderService::saveUnifiedBlocks('blog_category', 0, $blogBlocks);
        
        $blogPostBlocks = [
            [
                'block_type' => 'editorial_split_text_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Article',
                    'heading' => 'Featured Post',
                    'paragraph_1' => 'Read our latest thoughts on luxury outdoor living, material selection, and architectural precision.',
                ],
            ],
        ];
        BlockBuilderService::saveUnifiedBlocks('blog_post', 0, $blogPostBlocks);

        // 4. Portfolio Sub-pages
        $portfolioCategoryBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Curated Works',
                    'heading' => 'Portfolio Category',
                ],
            ],
            [
                'block_type' => 'portfolio_masonry_grid_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Recent Installations',
                    'heading' => 'Explore the Gallery',
                ],
            ],
        ];
        BlockBuilderService::saveUnifiedBlocks('portfolio_category', 0, $portfolioCategoryBlocks);

        $portfolioProjectBlocks = [
            [
                'block_type' => 'portfolio_project_hero_luxury',
                'is_enabled' => true,
                'content' => [],
            ],
            [
                'block_type' => 'project_spec_sheet_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Details',
                    'heading' => 'Project Specifications',
                ],
            ],
            [
                'block_type' => 'portfolio_gallery_masonry_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Gallery',
                    'heading' => 'Visual Tour',
                ],
            ],
            [
                'block_type' => 'local_projects_carousel_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'More Work',
                    'heading' => 'Similar Projects',
                ],
            ]
        ];
        BlockBuilderService::saveUnifiedBlocks('portfolio_project', 0, $portfolioProjectBlocks);
    }
}