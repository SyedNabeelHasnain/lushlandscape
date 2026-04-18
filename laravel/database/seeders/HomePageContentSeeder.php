<?php

namespace Database\Seeders;

use App\Services\BlockBuilderService;
use Illuminate\Database\Seeder;

class HomePageContentSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            // Phase 3 FSE: The Luxury Hero
            [
                'block_type' => 'hero_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Design-Build Firm',
                    'heading' => 'Luxury Outdoor Living Built with',
                    'heading_highlight' => 'Precision',
                    'subtitle' => 'Landscape construction for architects, designers, and discerning homeowners across the Greater Toronto Area.',
                    'cta_primary_text' => 'Request Consultation',
                    'cta_primary_url' => '/consultation',
                    'cta_secondary_text' => 'View Projects →',
                    'cta_secondary_url' => '/portfolio',
                    'bg_pattern' => 'https://www.transparenttextures.com/patterns/cubes.png',
                    'badge_1_title' => 'Protected',
                    'badge_1_value' => '10-Year Warranty',
                    'badge_2_title' => 'Insured',
                    'badge_2_value' => '$5M Liability',
                    'badge_3_title' => 'Certified',
                    'badge_3_value' => 'WSIB Compliant',
                    'badge_4_title' => 'Trusted',
                    'badge_4_value' => 'Architect Alliance',
                ],
            ],

            // Phase 3 FSE: The Architectural Standard
            [
                'block_type' => 'architectural_standard',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'The Architectural Standard',
                    'heading' => 'Built for Properties Where',
                    'heading_highlight' => 'Detail Matters',
                    'paragraph' => 'Lush Landscape creates private residential outdoor environments where structure, craftsmanship, and visual restraint matter equally. From driveways, patios, and retaining walls to grading and planting, every project is approached with clarity, proportion, and long-term performance in mind.',
                ],
            ],

            // Phase 3 FSE: Core Disciplines
            [
                'block_type' => 'architectural_services',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Core Disciplines',
                    'heading' => 'Architectural<br>Solutions',
                    'description' => 'The complete realization of complex master plans, categorized into four signature disciplines.',
                    'card_1_icon' => 'fa-solid fa-vector-square',
                    'card_1_title' => 'Paving & Arrival',
                    'card_1_desc' => 'Interlocking driveways, natural stone, and porcelain surfaces engineered for entry sequences.',
                    'card_1_list' => '8,000 PSI Pavers, Open-Graded Bases',
                    'card_2_icon' => 'fa-solid fa-fire-burner',
                    'card_2_title' => 'Outdoor Living',
                    'card_2_desc' => 'Patios, culinary masonry, and atmospheric lighting crafted for seamless hospitality.',
                    'card_2_list' => 'Custom Masonry, Gas-Line Integration',
                    'card_3_icon' => 'fa-solid fa-mountain-sun',
                    'card_3_title' => 'Structural Corrective',
                    'card_3_desc' => 'Retaining walls, complex grading, and frost-mitigation ensuring absolute surface stability.',
                    'card_3_list' => 'Ravine Stabilization, Engineered Drainage',
                    'card_4_icon' => 'fa-solid fa-compass-drafting',
                    'card_4_title' => 'Estate Construction',
                    'card_4_desc' => 'The complete realization of complex master plans through rigorous project management.',
                    'card_4_list' => 'Turnkey Logistics, In-House Execution',
                    'slider_img_1' => 'https://images.unsplash.com/photo-1591825729269-caeb344f6df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                    'slider_img_2' => 'https://images.unsplash.com/photo-1511818966892-d7d671e672a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                    'slider_eyebrow' => 'Material Integrity',
                    'slider_heading' => 'Crafted with architectural precision & premium stone selection.',
                ],
            ],

            // Phase 3 FSE: Parallax Banner
            [
                'block_type' => 'parallax_banner_luxury',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'The Intersection',
                    'heading' => 'Designing the Space Between',
                    'heading_highlight' => 'Nature & Architecture.',
                    'bg_image' => 'https://images.unsplash.com/photo-1600607686527-6fb886090705?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp',
                ],
            ],

            // Phase 3 FSE: Portfolio Horizontal Slider
            [
                'block_type' => 'portfolio_slider',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Completed Works',
                    'heading' => 'Selected Portfolio',
                    'link_text' => 'Explore All Cases',
                    'link_url' => '/portfolio',
                    'item_1_img' => 'https://images.unsplash.com/photo-1600607688969-a5bfcd64bd28?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                    'item_1_eyebrow' => '01 / Old Oakville',
                    'item_1_title' => 'Contemporary Stone',
                    'item_2_img' => 'https://images.unsplash.com/photo-1598228723654-419b48f68e4c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                    'item_2_eyebrow' => '02 / Lorne Park',
                    'item_2_title' => 'Estate Backyard',
                    'item_3_img' => 'https://images.unsplash.com/photo-1511818966892-d7d671e672a2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                    'item_3_eyebrow' => '03 / Shoreacres',
                    'item_3_title' => 'Lakeside Luxury',
                    'item_4_img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                    'item_4_eyebrow' => '04 / Bridle Path',
                    'item_4_title' => 'Arrival Sequence',
                ],
            ],

            // Phase 3 FSE: Marquee
            [
                'block_type' => 'marquee_brand',
                'is_enabled' => true,
                'content' => [
                    'text' => 'ARCHITECTURAL PRECISION &nbsp;&nbsp;&bull;&nbsp;&nbsp; UNYIELDING QUALITY &nbsp;&nbsp;&bull;&nbsp;&nbsp; PREMIUM STONE SELECTION &nbsp;&nbsp;&bull;&nbsp;&nbsp; EXPERT CRAFTSMANSHIP &nbsp;&nbsp;&bull;&nbsp;&nbsp;',
                ],
            ],

            // Phase 3 FSE: Architectural Process
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

            // Phase 3 FSE: Executive Enclaves
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

            // Phase 3 FSE: Consultation Form Split
            [
                'block_type' => 'consultation_form_split',
                'is_enabled' => true,
                'data_source_id' => 'consultation', // Resolves to the 'consultation' form slug
                'content' => [
                    'eyebrow' => 'Initiate Project',
                    'heading' => 'Request a<br>Consultation',
                    'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours.',
                ],
            ],
        ];

        BlockBuilderService::saveUnifiedBlocks('home', 0, $blocks);
    }
}
