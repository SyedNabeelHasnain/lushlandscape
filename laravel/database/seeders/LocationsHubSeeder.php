<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\BlockBuilderService;

class LocationsHubSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            [
                'block_type' => 'parallax_banner_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Service Footprint',
                    'heading' => 'Building Across',
                    'heading_highlight' => 'The GTA.',
                    'bg_image' => 'https://images.unsplash.com/photo-1600607686527-6fb886090705?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp',
                ],
            ],
            [
                'block_type' => 'locations_grid_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Service Footprint',
                    'heading' => 'Geographical<br>Execution',
                    'description' => 'Providing landscape execution exclusively for the Greater Toronto Area\'s most established residential communities.',
                ],
            ],
            [
                'block_type' => 'enclaves_tabs',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'The Top 1% of the GTA',
                    'heading' => 'Executive Enclaves',
                    'description' => 'Providing landscape execution exclusively for the Greater Toronto Area\'s most established residential communities.',
                    'tab_1_name' => 'Oakville',
                    'tab_1_items' => 'Morrison, South West Oakville, Old Oakville, Ford',
                    'tab_2_name' => 'Mississauga',
                    'tab_2_items' => 'Lorne Park, Mineola West, Gordon Woods, Credit Mills',
                    'tab_3_name' => 'Toronto',
                    'tab_3_items' => 'The Bridle Path, Forest Hill, Lawrence Park, Yorkville',
                    'tab_4_name' => 'Burlington',
                    'tab_4_items' => 'Shoreacres, Roseland, Tyandaga, Downtown',
                    'tab_5_name' => 'Vaughan',
                    'tab_5_items' => 'Kleinburg, Islington Woods, Patterson, Vellore Village',
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

        BlockBuilderService::saveUnifiedBlocks('locations_hub', 0, $blocks);
    }
}