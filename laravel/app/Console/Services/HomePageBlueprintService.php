<?php

namespace App\Console\Services;

use App\Models\PageBlock;
use App\Models\PortfolioProject;
use App\Models\Setting;
use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\DB;

class HomePageBlueprintService
{
    /**
     * Build the premium homepage blueprint using the approved design language.
     *
     * @return array<int, array<string, mixed>>
     */
    public function build(): array
    {
        $showcaseMediaIds = $this->showcaseMediaIds();
        $heroMediaId = $showcaseMediaIds[0] ?? null;
        $featureMediaId = $showcaseMediaIds[1] ?? ($showcaseMediaIds[0] ?? null);
        $sliderMediaIds = array_slice($showcaseMediaIds, 1, 3);

        $blocks = [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Luxury Outdoor Living<br>Built with <span class="italic">Precision</span>',
                    'subheadline' => 'Landscape construction for architects, designers, and discerning homeowners across the Greater Toronto Area.',
                    'media_id' => $heroMediaId,
                    'video_url' => '',
                    'parallax_intensity' => 'subtle',
                    'overlay_preset' => 'dark',
                ],
                $this->styles([
                    'spacing_preset' => 'none',
                    'padding_top' => 'none',
                    'padding_bottom' => 'none',
                    'margin_bottom' => 'none',
                    'max_width' => 'full',
                    'surface_preset' => 'transparent',
                ]),
                customId: 'home-hero'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Core Disciplines',
                    'heading' => 'Architectural Hardscaping & Structural Solutions',
                    'subtitle' => 'From urban ravine lots to expansive executive estates, we engineer outdoor environments that endure.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => true,
                    'card_cta_label' => 'Explore Discipline',
                    'tone' => 'cream',
                    'show_category_nav' => false,
                    'show_view_all' => true,
                    'view_all_text' => 'View All Core Disciplines',
                    'view_all_url' => '/services',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'services'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Elite Standards',
                    'heading' => 'The Architectural Integrity Layer',
                    'description' => 'We collaborate with architects, designers, and discerning homeowners to deliver structurally sound outdoor spaces with premium material discipline. Every project is engineered to respect architectural intent, municipal grading, and long-term performance.',
                    'media_id' => $featureMediaId,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Learn About Our Standards',
                    'cta_url' => '/about',
                ],
                $this->styles([
                    'max_width' => 'full',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'editorial'
            ),
            $this->block(
                'portfolio_gallery',
                [
                    'eyebrow' => 'Selected Works',
                    'heading' => 'Engineered Environments',
                    'subtitle' => 'A curated selection of our completed landscape construction and hardscaping projects across the GTA.',
                    'layout' => 'rail',
                    'columns' => '3',
                    'variant' => 'editorial',
                    'tone' => 'dark',
                    'show_view_all' => true,
                    'view_all_text' => 'View Full Portfolio',
                    'view_all_url' => '/portfolio',
                ],
                $this->styles([
                    'max_width' => 'full',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'forest-gradient',
                ]),
                customId: 'portfolio'
            ),
        ];

        $blocks[] = $this->block(
            'authority_grid',
            [
                'eyebrow' => 'Our Commitments',
                'heading' => 'Built to Endure',
                'introduction' => 'Professional discipline, premium materials, and accountable execution are built into every phase of our delivery.',
                'card_skin' => 'elevated',
                'items' => [
                    [
                        'icon' => 'shield-check',
                        'title' => 'Licensed & Insured',
                        'description' => 'Fully insured job sites and disciplined site protection.',
                    ],
                    [
                        'icon' => 'award',
                        'title' => '10-Year Warranty',
                        'description' => 'Workmanship protection that reflects premium delivery standards.',
                    ],
                    [
                        'icon' => 'ruler',
                        'title' => 'Precision Base Work',
                        'description' => 'Engineered preparation that supports long-term stability.',
                    ],
                    [
                        'icon' => 'handshake',
                        'title' => 'Fixed-Scope Clarity',
                        'description' => 'Transparent scopes, clean sequencing, and accountable communication.',
                    ],
                ],
            ],
            $this->styles([
                'max_width' => 'xl',
                'spacing_preset' => 'feature',
                'surface_preset' => 'white',
            ]),
            customId: 'authority'
        );

        $blocks[] = $this->block(
            'process_steps',
            [
                'eyebrow' => 'Project Rhythm',
                'heading' => 'A structured path from consultation to completion',
                'subtitle' => 'Every engagement follows a disciplined sequence so design intent, pricing clarity, and site execution stay aligned.',
                'variant' => 'premium-stack',
                'tone' => 'light',
                'steps' => [
                    [
                        'icon' => 'messages-square',
                        'title' => 'On-Site Consultation',
                        'desc' => 'We assess the property, understand the lifestyle goals, and identify site realities before proposing scope.',
                    ],
                    [
                        'icon' => 'clipboard-list',
                        'title' => 'Scope & Estimate',
                        'desc' => 'You receive a detailed plan with material direction, budget clarity, and a buildable execution path.',
                    ],
                    [
                        'icon' => 'pickaxe',
                        'title' => 'Construction Delivery',
                        'desc' => 'Our team handles excavation, base prep, masonry, and finishing with consistent site discipline.',
                    ],
                    [
                        'icon' => 'shield-check',
                        'title' => 'Final Walkthrough',
                        'desc' => 'We review the completed work, close out details, and leave you with confidence in the finished result.',
                    ],
                ],
            ],
            $this->styles([
                'max_width' => 'xl',
                'spacing_preset' => 'section',
                'surface_preset' => 'white',
            ]),
            customId: 'process'
        );

        $blocks[] = $this->block(
            'service_area_enclave',
            [
                'eyebrow' => 'Service Areas',
                'heading' => 'Serving Greater Toronto’s Premier Enclaves',
                'support_copy' => 'Specialized authority in Mississauga, Oakville, Burlington, Toronto, Milton, and surrounding luxury neighborhoods.',
                'presentation_mode' => 'tabbed-enclave',
            ],
            $this->styles([
                'max_width' => 'xl',
                'spacing_preset' => 'section',
                'surface_preset' => 'cream',
            ]),
            customId: 'locations'
        );

        $blocks[] = $this->block(
            'split_consultation_panel',
            [
                'eyebrow' => 'Get Started',
                'heading' => 'Start Your Landscape Transformation',
                'editorial_copy' => 'Schedule an on-site consultation to discuss your vision, technical requirements, and how we can elevate your property. We respect your time and provide clear, actionable next steps.',
                'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                'media_id' => $heroMediaId,
                'form_slug' => 'contact-us',
                'tone' => 'dark',
            ],
            $this->styles([
                'max_width' => 'full',
                'spacing_preset' => 'none',
                'padding_top' => 'none',
                'padding_bottom' => 'none',
                'margin_bottom' => 'none',
                'surface_preset' => 'transparent',
            ]),
            customId: 'contact'
        );

        return $blocks;
    }

    /**
     * Scaffold the homepage blueprint onto the live home singleton only when safe.
     *
     * @return array<string, mixed>
     */
    public function scaffold(bool $replace = false): array
    {
        $existingUnifiedBlocks = PageBlock::forPage('home', null)->count();
        $legacyCounts = BlockBuilderService::legacyRowCounts('home', null);
        $legacyTotal = array_sum($legacyCounts);

        if (! $replace && ($existingUnifiedBlocks > 0 || $legacyTotal > 0)) {
            return [
                'applied' => false,
                'replaced' => false,
                'block_count' => 0,
                'existing_unified_blocks' => $existingUnifiedBlocks,
                'legacy_counts' => $legacyCounts,
                'reason' => 'existing_content',
            ];
        }

        $blocks = $this->build();

        DB::transaction(function () use ($blocks, $replace) {
            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('home', null);
            }

            BlockBuilderService::saveUnifiedBlocks('home', null, $blocks);
            $this->upsertSeoDefaults($replace);
        });

        return [
            'applied' => true,
            'replaced' => $replace,
            'block_count' => count($blocks),
            'existing_unified_blocks' => $existingUnifiedBlocks,
            'legacy_counts' => $legacyCounts,
            'reason' => 'scaffolded',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function seoDefaults(): array
    {
        return [
            'seo_home_title' => 'Lush Landscapes | Luxury Landscape Construction & Hardscape Design GTA',
            'seo_home_description' => 'Premium landscape construction for architects, designers, and discerning homeowners in Toronto, Oakville, Mississauga, and Milton. Specializing in interlocking, retaining walls, and outdoor living.',
            'seo_home_og_title' => 'Lush Landscapes | Luxury Landscape Construction',
            'seo_home_og_description' => 'Architectural precision in outdoor living across the Greater Toronto Area. 10-Year Workmanship Warranty.',
        ];
    }

    /**
     * @return array<int, int>
     */
    private function showcaseMediaIds(): array
    {
        return PortfolioProject::query()
            ->where('status', 'published')
            ->whereNotNull('hero_media_id')
            ->orderByDesc('is_featured')
            ->orderByDesc('completion_date')
            ->orderBy('sort_order')
            ->limit(6)
            ->pluck('hero_media_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function block(
        string $type,
        array $content = [],
        array $styles = [],
        array $children = [],
        ?string $customId = null,
    ): array {
        return [
            'block_type' => $type,
            'category' => BlockBuilderService::typeConfig($type)['category'] ?? 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => $content,
            'styles' => $styles !== [] ? $styles : BlockBuilderService::styleDefaults(),
            'custom_id' => $customId,
            'children' => $children,
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function styles(array $desktopOverrides = [], array $tabletOverrides = [], array $mobileOverrides = []): array
    {
        $defaults = BlockBuilderService::styleDefaults();

        return [
            'desktop' => array_merge($defaults['desktop'] ?? [], $desktopOverrides),
            'tablet' => array_merge($defaults['tablet'] ?? [], $tabletOverrides),
            'mobile' => array_merge($defaults['mobile'] ?? [], $mobileOverrides),
        ];
    }

    private function upsertSeoDefaults(bool $replace): void
    {
        foreach ($this->seoDefaults() as $key => $value) {
            $existingValue = (string) Setting::get($key, '');
            if (! $replace && $existingValue !== '') {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => 'seo',
                    'type' => str_contains($key, 'description') ? 'textarea' : 'text',
                    'label' => 'Home Page: '.ucwords(str_replace(['seo_home_', '_'], ['', ' '], $key)),
                    'value' => $value,
                ]
            );
        }

        Setting::flushCache();
    }
}
