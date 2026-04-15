<?php

namespace Database\Seeders\Content;

use App\Models\City;
use App\Models\Service;
use App\Models\ServiceCityPage;
use App\Services\BlockBuilderService;

/**
 * Generates standard content blocks for service-city pages.
 * Each page gets a modular set of blocks that can be edited via admin.
 */
class ContentBlockHelper
{
    /**
     * Create standard content blocks for a service-city page.
     */
    public static function createBlocks(
        ServiceCityPage $page,
        Service $service,
        City $city,
        array $options = []
    ): void {
        $cityName = $city->name;
        $serviceName = $service->name;
        $neighborhoods = $options['neighborhoods'] ?? [];
        $processSteps = $options['process_steps'] ?? self::defaultProcessSteps($serviceName);
        $testimonial = $options['testimonial'] ?? self::defaultTestimonial($serviceName, $cityName);
        $materials = $options['materials'] ?? [];

        $blocks = [];

        $blocks[] = [
            'block_type' => 'process_steps',
            'is_enabled' => true,
            'content' => [
                'eyebrow' => 'Our Process',
                'heading' => "Our {$serviceName} Process in {$cityName}",
                'subtitle' => '',
                'variant' => 'numbered',
                'tone' => 'light',
                'steps' => array_map(fn (array $step) => [
                    'icon' => $step['icon'] ?? '',
                    'title' => $step['title'] ?? '',
                    'desc' => $step['description'] ?? '',
                ], $processSteps),
            ],
        ];

        if (! empty($materials)) {
            $blocks[] = [
                'block_type' => 'cards_grid',
                'is_enabled' => true,
                'content' => [
                    'heading' => "Material Options for Your {$cityName} Project",
                    'subtitle' => '',
                    'columns' => '3',
                    'variant' => 'editorial',
                    'tone' => 'light',
                    'cards' => array_map(fn (array $card) => [
                        'meta' => $card['meta'] ?? '',
                        'title' => $card['title'] ?? '',
                        'description' => $card['description'] ?? ($card['text'] ?? ''),
                        'icon' => $card['icon'] ?? '',
                        'media_id' => $card['media_id'] ?? null,
                        'link_text' => $card['link_text'] ?? '',
                        'link_url' => $card['link_url'] ?? '',
                    ], $materials),
                ],
            ];
        }

        $blocks[] = [
            'block_type' => 'cta_section',
            'is_enabled' => true,
            'content' => [
                'eyebrow' => 'Project Planning',
                'title' => "Ready to plan your {$serviceName} project in {$cityName}?",
                'subtitle' => 'We can help define scope, material direction, and the best path from consultation to construction.',
                'variant' => 'split',
                'tone' => 'cream',
                'button_text' => 'Book a Consultation',
                'button_url' => '/contact',
                'button_secondary_text' => '',
                'button_secondary_url' => '',
            ],
        ];

        $blocks[] = [
            'block_type' => 'testimonials',
            'is_enabled' => true,
            'content' => [
                'eyebrow' => 'Client Feedback',
                'heading' => "What {$cityName} homeowners say",
                'subtitle' => '',
                'layout' => 'grid',
                'featured_only' => true,
                'variant' => 'editorial',
                'tone' => 'cream',
            ],
        ];

        if (! empty($neighborhoods)) {
            $blocks[] = [
                'block_type' => 'area_served',
                'is_enabled' => true,
                'content' => [
                    'heading' => "{$serviceName} across {$cityName} neighbourhoods",
                    'description' => "We provide {$serviceName} throughout {$cityName}, including the following communities.",
                    'columns' => '3',
                    'areas' => array_map(fn (string $name) => ['name' => $name], $neighborhoods),
                ],
            ];
        }

        $blocks[] = [
            'block_type' => 'feature_list',
            'is_enabled' => true,
            'content' => [
                'heading' => "Why {$cityName} Homeowners Choose Lush Landscape",
                'columns' => '2',
                'variant' => 'editorial',
                'features' => [
                    ['icon' => 'shield-check', 'title' => '10-Year Workmanship Warranty', 'description' => "Every {$serviceName} project is backed by our comprehensive warranty."],
                    ['icon' => 'award', 'title' => 'ICPI & Landscape Ontario Certified', 'description' => 'Industry-certified crews delivering professional-grade results.'],
                    ['icon' => 'map-pin', 'title' => "Local {$cityName} Expertise", 'description' => "We understand {$cityName}'s soil conditions, bylaws, and neighbourhood character."],
                    ['icon' => 'clock', 'title' => 'On-Time Project Completion', 'description' => 'Clear timelines with regular communication from start to finish.'],
                ],
            ],
        ];

        BlockBuilderService::saveUnifiedBlocks('service_city_page', $page->id, $blocks);
    }

    private static function defaultProcessSteps(string $serviceName): array
    {
        return [
            ['title' => 'On-Site Consultation & Site Assessment', 'description' => "We visit your property, discuss your vision for {$serviceName}, and evaluate site conditions including soil, grading, and access.", 'icon' => 'clipboard-check'],
            ['title' => 'Scope Plan & Proposal', 'description' => 'Our team prepares a clear scope plan with material selections, layout direction, and a detailed proposal.', 'icon' => 'pencil-ruler'],
            ['title' => 'Professional Installation', 'description' => 'Our certified crews handle excavation, base preparation, and installation following manufacturer specifications and industry best practices.', 'icon' => 'hard-hat'],
            ['title' => 'Final Inspection & Warranty', 'description' => 'A thorough walkthrough ensures every detail meets our standards. You receive your 10-year workmanship warranty documentation.', 'icon' => 'check-circle'],
        ];
    }

    private static function defaultTestimonial(string $serviceName, string $cityName): array
    {
        return [
            'quote' => 'The Lush Landscape team transformed our property with exceptional craftsmanship. The entire process from consultation to completion was professional and the results exceeded our expectations.',
            'author' => "Satisfied {$cityName} Homeowner",
            'role' => "{$serviceName} Project",
            'rating' => '5',
            'style' => 'card',
        ];
    }

    /**
     * Generate default SEO keywords for a service-city page.
     */
    public static function defaultKeywords(string $serviceName, string $cityName): array
    {
        $svc = strtolower($serviceName);
        $city = strtolower($cityName);

        return [
            'primary' => [
                "{$svc} {$city}",
                "{$svc} in {$city}",
            ],
            'secondary' => [
                "{$svc} {$city} ontario",
                "{$city} {$svc} company",
                "{$svc} contractor {$city}",
                "{$svc} services {$city}",
            ],
            'long_tail' => [
                "how much does {$svc} cost in {$city}",
                "best {$svc} company in {$city} ontario",
                "{$svc} near me {$city}",
            ],
        ];
    }
}
