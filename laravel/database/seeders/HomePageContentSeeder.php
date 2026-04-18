<?php

namespace Database\Seeders;

use App\Services\BlockBuilderService;
use Illuminate\Database\Seeder;

class HomePageContentSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            // Block 1: Welcome / introductory rich text (disabled — replaced by about-section component)
            [
                'block_type' => 'rich_text',
                'is_enabled' => false,
                'content' => [
                    'html' => '<h2>Your Trusted Partner for Outdoor Living in Ontario</h2>'
                        .'<p>At Lush Landscape Service, we believe your outdoor space should be an extension of the life you love. '
                        .'Whether you are dreaming of a welcoming interlocking driveway, a backyard retreat with natural stone, '
                        .'or a complete landscape transformation, our team brings the craftsmanship and local expertise to make it happen.</p>'
                        .'<p>Serving homeowners across the Greater Toronto and Hamilton Area since 2018, we combine premium materials '
                        .'with meticulous installation practices to deliver results that look stunning on day one and hold up for decades. '
                        .'Every project begins with an on-site consultation where we listen to your goals, assess your property, '
                        .'and present a clear scope plan with thoughtful material direction.</p>'
                        .'<p>From concept through completion, you work directly with our experienced project leads. '
                        .'No subcontractor surprises, no hidden fees, and a workmanship warranty that gives you lasting peace of mind.</p>',
                ],
            ],

            // Block 2: Why Choose Lush feature list
            [
                'block_type' => 'feature_list',
                'is_enabled' => true,
                'content' => [
                    'heading' => 'Why Ontario Homeowners Choose Lush Landscape',
                    'columns' => '2',
                    'features' => [
                        [
                            'icon' => 'shield-check',
                            'title' => 'Licensed and Fully Insured',
                            'description' => 'We carry comprehensive liability coverage and WSIB clearance on every job site, protecting your property and your investment from start to finish.',
                        ],
                        [
                            'icon' => 'award',
                            'title' => 'Landscape Ontario Member',
                            'description' => 'As active members of Landscape Ontario, we adhere to the highest industry standards for installation quality, environmental stewardship, and professional conduct.',
                        ],
                        [
                            'icon' => 'calendar-check',
                            'title' => 'On-Time Project Delivery',
                            'description' => 'We provide a clear project timeline before work begins and stick to it. Most residential projects are completed within the projected schedule, weather permitting.',
                        ],
                        [
                            'icon' => 'ruler',
                            'title' => 'Precision Base Preparation',
                            'description' => 'Our installations start with proper excavation, graded gravel base, and compacted bedding. This foundational work prevents settling, shifting, and drainage issues over time.',
                        ],
                        [
                            'icon' => 'handshake',
                            'title' => 'Clear Scope & Proposal',
                            'description' => 'Your scope plan outlines materials, labour, permits, and site cleanup. Milestones are agreed upon before work begins, and any changes are reviewed and approved in writing.',
                        ],
                        [
                            'icon' => 'shield',
                            'title' => 'Up to 10-Year Workmanship Warranty',
                            'description' => 'We stand behind every project with a written warranty. If anything we installed does not perform as expected within the warranty period, we return and make it right at no cost.',
                        ],
                    ],
                ],
            ],

            // Block 3: Numbers / stats counter
            [
                'block_type' => 'number_counter',
                'is_enabled' => true,
                'content' => [
                    'bg' => 'forest',
                    'counters' => [
                        [
                            'target' => '500',
                            'suffix' => '+',
                            'label' => 'Projects Completed',
                            'icon' => 'check-circle',
                        ],
                        [
                            'target' => '10',
                            'suffix' => '',
                            'label' => 'Cities Served Across Ontario',
                            'icon' => 'map-pin',
                        ],
                        [
                            'target' => '4.9',
                            'suffix' => '/5',
                            'label' => 'Average Google Rating',
                            'icon' => 'star',
                        ],
                        [
                            'target' => '8',
                            'suffix' => '+',
                            'label' => 'Years of Outdoor Expertise',
                            'icon' => 'calendar',
                        ],
                    ],
                ],
            ],

            // Block 4: Call-to-action banner
            [
                'block_type' => 'cta_section',
                'is_enabled' => true,
                'content' => [
                    'eyebrow' => 'Next Step',
                    'title' => 'Ready to transform your outdoor space?',
                    'subtitle' => 'Book your on-site consultation and receive a clear scope plan with thoughtful material direction.',
                    'variant' => 'split',
                    'tone' => 'cream',
                    'button_text' => 'Book a Consultation',
                    'button_url' => '/consultation',
                    'button_secondary_text' => '',
                    'button_secondary_url' => '',
                ],
            ],

            // Block 5: Area served (disabled — merged into city-grid component)
            [
                'block_type' => 'area_served',
                'is_enabled' => false,
                'content' => [
                    'heading' => 'Proudly Serving Communities Across Southern Ontario',
                    'description' => 'From the shores of Burlington to the growing neighbourhoods of Brampton, our crews deliver the same quality craftsmanship to every community we serve. Select your city to explore our local services and recent projects.',
                    'columns' => '3',
                    'areas' => [
                        ['name' => 'Hamilton',      'url' => '/landscaping-hamilton'],
                        ['name' => 'Burlington',    'url' => '/landscaping-burlington'],
                        ['name' => 'Oakville',      'url' => '/landscaping-oakville'],
                        ['name' => 'Mississauga',   'url' => '/landscaping-mississauga'],
                        ['name' => 'Milton',         'url' => '/landscaping-milton'],
                        ['name' => 'Toronto',        'url' => '/landscaping-toronto'],
                        ['name' => 'Vaughan',        'url' => '/landscaping-vaughan'],
                        ['name' => 'Richmond Hill',  'url' => '/landscaping-richmond-hill'],
                        ['name' => 'Georgetown',     'url' => '/landscaping-georgetown'],
                        ['name' => 'Brampton',       'url' => '/landscaping-brampton'],
                    ],
                ],
            ],
            // Block 6: Interactive service area map (disabled — merged into city-grid component)
            [
                'block_type' => 'interactive_map',
                'is_enabled' => false,
                'content' => [
                    'heading' => 'Our Service Coverage Across Southern Ontario',
                    'description' => 'Explore the communities we serve. Select a city to see the neighbourhoods where our crews are actively completing projects, then contact us for a free on-site consultation.',
                    'map_mode' => 'all_cities',
                    'city_slug' => '',
                    'center_lat' => '43.55',
                    'center_lng' => '-79.65',
                    'zoom' => '9',
                    'height' => '520',
                    'show_chips' => true,
                    'marker_color' => 'forest',
                    'popup_cta_text' => 'Book a Consultation',
                    'schema_type' => 'LocalBusiness',
                    'markers' => [],
                ],
            ],
        ];

        BlockBuilderService::saveUnifiedBlocks('home', 0, $blocks);
    }
}
