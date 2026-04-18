<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaticPage;
use App\Services\BlockBuilderService;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        // Get the about page ID
        $aboutPage = StaticPage::where('slug', 'about-us')->first();
        if (!$aboutPage) {
            $aboutPage = StaticPage::create([
                'title' => 'Our Heritage',
                'slug' => 'about-us',
                'status' => 'published',
                'is_indexable' => true,
                'meta_title' => 'About Lush Landscape | Luxury Landscape Construction in GTA',
                'meta_description' => 'Discover the heritage of Lush Landscape, a premier design-build firm specializing in high-end residential outdoor environments across the Greater Toronto Area.',
            ]);
        }

        $blocks = [
            [
                'block_type' => 'parallax_banner_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Our Heritage',
                    'heading' => 'Building the',
                    'heading_highlight' => 'Architectural Standard.',
                    'bg_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp',
                ],
            ],
            [
                'block_type' => 'editorial_split_text_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'The Philosophy',
                    'heading' => 'Engineered for',
                    'heading_highlight' => 'Longevity.',
                    'paragraph_1' => 'Lush Landscape creates private residential outdoor environments where structure, craftsmanship, and visual restraint matter equally.',
                    'paragraph_2' => 'We reject the transactional nature of the landscaping industry. Instead, we operate as a design-build firm committed to long-term architectural integrity, executing complex master plans with absolute precision.',
                    'signature_name' => 'The Lush Team',
                    'signature_title' => 'Master Stonemasons & Builders',
                    'image' => 'https://images.unsplash.com/photo-1600607686527-6fb886090705?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                    'image_position' => 'left',
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
                'block_type' => 'timeline_history_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Our Evolution',
                    'heading' => 'A Heritage of<br>Craftsmanship',
                    'year_1' => '2018',
                    'title_1' => 'The Foundation',
                    'desc_1' => 'Lush Landscape Service was established with a singular focus on elevating residential stonework and structural hardscaping in the Greater Toronto Area.',
                    'year_2' => '2021',
                    'title_2' => 'Scale & Maturation',
                    'desc_2' => 'Expanded our core operations to handle full estate transformations, integrating luxury outdoor living spaces and high-end pool construction into our repertoire.',
                    'year_3' => '2024',
                    'title_3' => 'The Architectural Standard',
                    'desc_3' => 'Solidified our position as the design-build firm of choice for the top 1% of the GTA, partnering exclusively with prestige properties and heritage estates.',
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

        BlockBuilderService::saveUnifiedBlocks('static_page', $aboutPage->id, $blocks);
    }
}