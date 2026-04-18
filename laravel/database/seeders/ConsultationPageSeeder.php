<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BlockBuilderService;

class ConsultationPageSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            [
                'block_type' => 'consultation_wizard_luxury',
                'is_enabled' => true,
                'data_source_id' => 'consultation', // Resolves to the 'consultation' form slug
                'content' => [
                    'eyebrow' => 'Project Intake',
                    'heading' => 'Request a<br>Design Consultation',
                    'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours to schedule an on-site assessment.',
                    'badge_1_icon' => 'shield-check',
                    'badge_1_text' => '10-Year Warranty',
                    'badge_2_icon' => 'file-check-2',
                    'badge_2_text' => '$5M Liability Insured',
                    'badge_3_icon' => 'award',
                    'badge_3_text' => 'WSIB Compliant',
                    'badge_4_icon' => 'hard-hat',
                    'badge_4_text' => 'In-House Execution',
                ],
            ]
        ];

        BlockBuilderService::saveUnifiedBlocks('consultation', 0, $blocks);
    }
}