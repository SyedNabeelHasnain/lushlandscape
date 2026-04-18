<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BlockBuilderService;

class ServicePagesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Default layout for Service Category Pages (/services/{category})
        $categoryBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [],
            ],
            [
                'block_type' => 'service_category_cards_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Core Services',
                    'heading' => 'Specialized Disciplines',
                ],
            ],
            [
                'block_type' => 'local_projects_carousel_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Category Portfolio',
                    'heading' => 'Recent Installations',
                ],
            ],
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source' => [],
                'content' => [
                    'eyebrow' => 'Initiate Project',
                    'heading' => 'Request a<br>Consultation',
                    'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours.',
                ],
            ]
        ];

        BlockBuilderService::saveUnifiedBlocks('service_category', 0, $categoryBlocks);

        // 2. Default layout for Individual Service Pages (/services/{category}/{service})
        $serviceBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [],
            ],
            [
                'block_type' => 'architectural_standard',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'The Standard',
                    'heading' => 'Built for Properties Where',
                    'heading_highlight' => 'Detail Matters',
                    'paragraph' => 'Lush Landscape creates private residential outdoor environments where structure, craftsmanship, and visual restraint matter equally. Every project is approached with clarity, proportion, and long-term performance in mind.',
                ],
            ],
            [
                'block_type' => 'local_projects_carousel_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Service Portfolio',
                    'heading' => 'Recent Installations',
                ],
            ],
            [
                'block_type' => 'local_faq_accordion_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Service Guidelines',
                    'heading' => 'Common Inquiries',
                ],
            ],
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source' => [],
                'content' => [
                    'eyebrow' => 'Initiate Project',
                    'heading' => 'Request a<br>Consultation',
                    'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours.',
                ],
            ]
        ];

        BlockBuilderService::saveUnifiedBlocks('service', 0, $serviceBlocks);
        
        // 3. Static Page Default
        $staticBlocks = [
            [
                'block_type' => 'editorial_split_text_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Information',
                    'heading' => 'Company Details',
                    'paragraph_1' => 'Our commitment to quality extends beyond our craftsmanship to our business practices and client relationships.',
                ],
            ],
        ];
        BlockBuilderService::saveUnifiedBlocks('static_page', 0, $staticBlocks);
    }
}