<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BlockBuilderService;

class ServicesHubSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed the Main Services Hub (/services)
        $hubBlocks = [
            [
                'block_type' => 'parallax_banner_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Our Capabilities',
                    'heading' => 'The Complete',
                    'heading_highlight' => 'Master Plan.',
                    'bg_image' => 'https://images.unsplash.com/photo-1591825729269-caeb344f6df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp',
                ],
            ],
            [
                'block_type' => 'service_category_cards_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Our Capabilities',
                    'heading' => 'Architectural<br>Disciplines',
                    'description' => 'Explore our core construction categories, engineered for longevity and absolute visual restraint.',
                ],
            ],
            [
                'block_type' => 'architectural_process',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Methodology',
                    'heading' => 'A Refined<br>Process',
                    'description' => 'Designed to keep decision-making calm, informed, and rigorously managed from initiation to final aftercare.',
                    'step_1_phase' => 'Phase 01',
                    'step_1_title' => 'Initial Consultation',
                    'step_1_desc' => 'A focused discussion around the property, the scope being considered, and the architectural atmosphere you wish the environment to carry.',
                    'step_2_phase' => 'Phase 02',
                    'step_2_title' => 'Site Review & Design',
                    'step_2_desc' => 'We assess circulation, structural grade, material intent, visual hierarchy, and practical site conditions to establish clear direction.',
                    'step_3_phase' => 'Phase 03',
                    'step_3_title' => 'Scope Development',
                    'step_3_desc' => 'The principal construction elements, project sequencing, and financial path are defined with absolute clarity, so expectations remain aligned.',
                    'step_4_phase' => 'Phase 04',
                    'step_4_title' => 'Precision Execution',
                    'step_4_desc' => 'Construction is managed by our in-house teams with severe discipline, site control, and unyielding attention to detail.',
                    'step_5_phase' => 'Phase 05',
                    'step_5_title' => 'Completion & Aftercare',
                    'step_5_desc' => 'The environment is reviewed, refined, and transitioned to you with practical guidance for long-term architectural confidence.',
                ],
            ],
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source_id' => 'consultation',
                'content' => [
                    'eyebrow' => 'Initiate Project',
                    'heading' => 'Request a<br>Consultation',
                    'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours.',
                ],
            ]
        ];

        BlockBuilderService::saveUnifiedBlocks('services_hub', 0, $hubBlocks);

        // 2. Seed the Service Category Default Layout (/services/{category})
        $categoryBlocks = [
            [
                'block_type' => 'parallax_banner_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Discipline Overview',
                    'heading' => 'Specialized',
                    'heading_highlight' => 'Execution.',
                    'bg_image' => 'https://images.unsplash.com/photo-1598228723654-419b48f68e4c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp',
                ],
            ],
            [
                'block_type' => 'service_list_masonry_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Specialized Services',
                    'heading' => 'Targeted<br>Execution',
                    'description' => 'Explore the specific installations and capabilities within this architectural discipline.',
                ],
            ],
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source_id' => 'consultation',
                'content' => [
                    'eyebrow' => 'Initiate Project',
                    'heading' => 'Request a<br>Consultation',
                    'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours.',
                ],
            ]
        ];

        BlockBuilderService::saveUnifiedBlocks('service_category', 0, $categoryBlocks);
    }
}