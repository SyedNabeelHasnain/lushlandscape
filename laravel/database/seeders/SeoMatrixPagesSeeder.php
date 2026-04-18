<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BlockBuilderService;

class SeoMatrixPagesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed the default layout for the City Landing Page (/landscaping-{city})
        $cityBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [],
            ],
            [
                'block_type' => 'architectural_standard',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'The Local Standard',
                    'heading' => 'Built for Properties Where',
                    'heading_highlight' => 'Detail Matters',
                    'paragraph' => 'Lush Landscape creates private residential outdoor environments where structure, craftsmanship, and visual restraint matter equally. From driveways, patios, and retaining walls to grading and planting, every project is approached with clarity, proportion, and long-term performance in mind.',
                ],
            ],
            [
                'block_type' => 'local_projects_carousel_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Local Portfolio',
                    'heading' => 'Recent Installations in [City]',
                ],
            ],
            [
                'block_type' => 'credentials_grid_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'The Firm',
                    'heading' => 'Institutional Grade Security',
                    'cred_1_icon' => 'shield-check',
                    'cred_1_title' => '10-Year Workmanship Warranty',
                    'cred_1_desc' => 'Every structural installation is backed by a rigorous, written decade-long warranty, ensuring complete peace of mind.',
                    'cred_2_icon' => 'file-check-2',
                    'cred_2_title' => '$5M Liability Insurance',
                    'cred_2_desc' => 'We carry comprehensive coverage specifically designed for complex residential and estate-level construction.',
                    'cred_3_icon' => 'award',
                    'cred_3_title' => 'WSIB Cleared & Compliant',
                    'cred_3_desc' => 'Full Workers\' Safety and Insurance Board clearance guarantees our in-house teams are protected and professional.',
                    'cred_4_icon' => 'hard-hat',
                    'cred_4_title' => 'In-House Execution',
                    'cred_4_desc' => 'We do not broker out our core trades. Our master stonemasons and structural teams are dedicated Lush Landscape personnel.',
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

        BlockBuilderService::saveUnifiedBlocks('city', 0, $cityBlocks);

        // 2. Seed the default layout for the Matrix Service-City Page (/{slug})
        $matrixBlocks = [
            [
                'block_type' => 'local_seo_hero_luxury',
                'is_enabled' => true,
                'content' => [],
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
                'block_type' => 'local_projects_carousel_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Local Portfolio',
                    'heading' => 'Recent Installations in [City]',
                ],
            ],
            [
                'block_type' => 'local_faq_accordion_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Project Guidelines',
                    'heading' => 'Common Inquiries',
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

        BlockBuilderService::saveUnifiedBlocks('service_city_page', 0, $matrixBlocks);
    }
}