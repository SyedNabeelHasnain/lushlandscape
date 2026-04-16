<?php

namespace App\Console\Services;

use App\Models\BlogCategory;
use App\Models\PageBlock;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\ServiceCategory;
use App\Services\BlockBuilderService;
use App\Services\SingletonPageBuilderService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ListingPageBlueprintService
{
    public function __construct(
        private readonly SingletonPageBuilderService $registry
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function scaffold(bool $replace = false): array
    {
        return DB::transaction(fn () => [
            'singleton_pages' => $this->scaffoldSingletonPages($replace),
            'taxonomy_pages' => $this->scaffoldTaxonomyPages($replace),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function scaffoldSingletonPages(bool $replace = false): array
    {
        $results = [];

        foreach ($this->registry->all() as $config) {
            $pageType = $config['page_type'];
            $pageId = $config['page_id'];
            $existingBlocks = PageBlock::forPage($pageType, $pageId)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[$config['key']] = [
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildSingletonPage($config['key']);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage($pageType, $pageId);
            }

            BlockBuilderService::saveUnifiedBlocks($pageType, $pageId, $blocks);

            $results[$config['key']] = [
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public function scaffoldTaxonomyPages(bool $replace = false): array
    {
        return [
            'service_categories' => $this->scaffoldServiceCategories($replace),
            'portfolio_categories' => $this->scaffoldPortfolioCategories($replace),
            'blog_categories' => $this->scaffoldBlogCategories($replace),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildSingletonPage(string $key): array
    {
        return match ($key) {
            'services-hub' => $this->buildServicesHub(),
            'locations-hub' => $this->buildLocationsHub(),
            'portfolio-index' => $this->buildPortfolioIndex(),
            'blog-index' => $this->buildBlogIndex(),
            'contact' => [],
            'consultation' => [],
            default => throw new \InvalidArgumentException("Unknown listing blueprint key [{$key}]."),
        };
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildPortfolioCategory(PortfolioCategory $category): array
    {
        return [
            $this->block(
                'portfolio_directory',
                [
                    'eyebrow' => 'Portfolio Category',
                    'heading' => $category->name,
                    'subtitle' => $category->short_description ?? '',
                    'tone' => 'light',
                    'show_filters' => false,
                    'show_featured_hero' => false,
                    'show_category_pills' => true,
                    'empty_title' => 'No projects published yet',
                    'empty_description' => 'This category is live, but it does not have any published portfolio projects yet.',
                    'empty_button_text' => 'Browse All Projects',
                    'empty_button_url' => '/portfolio',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'portfolio-directory'
            ),
            $this->block(
                'cta_section',
                [
                    'eyebrow' => 'Project Planning',
                    'title' => 'Planning a similar project?',
                    'subtitle' => 'We can help define scope, material direction, and execution strategy before construction begins.',
                    'variant' => 'split',
                    'tone' => 'cream',
                    'button_text' => 'Request a Consultation',
                    'button_url' => '/contact',
                    'button_secondary_text' => 'Contact Our Team',
                    'button_secondary_url' => '/contact',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'compact',
                ]),
                customId: 'portfolio-cta'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildBlogCategory(BlogCategory $category): array
    {
        return [
            $this->block(
                'blog_directory',
                [
                    'eyebrow' => 'Blog Category',
                    'heading' => $category->name,
                    'subtitle' => $category->short_description ?? '',
                    'tone' => 'light',
                    'show_featured_hero' => false,
                    'show_category_tabs' => true,
                    'empty_title' => 'No articles published yet',
                    'empty_description' => 'This category is live, but it does not have any published blog posts yet.',
                    'empty_button_text' => 'Browse All Articles',
                    'empty_button_url' => '/blog',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'blog-directory'
            ),
            $this->block(
                'cta_section',
                [
                    'eyebrow' => 'Need Project Advice?',
                    'title' => 'Talk through your ideas with our team.',
                    'subtitle' => 'If your question is project-specific, we can help you move from inspiration to a practical build plan.',
                    'variant' => 'panel',
                    'tone' => 'cream',
                    'button_text' => 'Request a Consultation',
                    'button_url' => '/contact',
                    'button_secondary_text' => 'Contact Us',
                    'button_secondary_url' => '/contact',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'compact',
                ]),
                customId: 'blog-cta'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildServiceCategory(ServiceCategory $category): array
    {
        return [
            $this->block(
                'hero',
                [
                    'eyebrow' => 'Service Category',
                    'heading' => $category->name,
                    'subtitle' => $category->short_description ?: 'A focused collection of premium landscaping services delivered with structural discipline and finish quality.',
                    'cta_primary_text' => 'Request a Consultation',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_text' => 'View Projects',
                    'cta_secondary_url' => '/portfolio',
                    'hero_media_id' => $category->hero_media_id,
                    'overlay_opacity' => '55',
                ],
                $this->styles([
                    'spacing_preset' => 'hero',
                    'max_width' => 'full',
                    'surface_style' => 'forest-gradient',
                ]),
                customId: 'service-category-hero'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Available Services',
                    'heading' => 'Explore '.$category->name,
                    'subtitle' => $category->long_description ?: 'Select the exact service that matches your project scope, property conditions, and desired finish level.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'architectural',
                    'tone' => 'light',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'service-category-grid'
            ),
            $this->block(
                'cta_section',
                [
                    'eyebrow' => 'Project Planning',
                    'title' => 'Ready to plan your '.$category->name.' project?',
                    'subtitle' => 'We can help define scope, material direction, and the best path from consultation to construction.',
                    'variant' => 'split',
                    'tone' => 'cream',
                    'button_text' => 'Request a Consultation',
                    'button_url' => '/contact',
                    'button_secondary_text' => 'Contact Our Team',
                    'button_secondary_url' => '/contact',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'compact',
                ]),
                customId: 'service-category-cta'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildServicesHub(): array
    {
        [$heroMediaId, $featureMediaId] = $this->showcaseMediaPair();

        $blocks = [
            $this->block(
                'hero',
                [
                    'heading' => 'Architectural Hardscaping & Outdoor Construction',
                    'subtitle' => 'Landscape construction for architects, designers, and discerning homeowners across the Greater Toronto Area.',
                    'eyebrow' => 'Core Disciplines',
                    'cta_primary_text' => 'Request a Consultation',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_text' => 'View Projects',
                    'cta_secondary_url' => '/portfolio',
                    'hero_media_id' => $heroMediaId,
                    'overlay_opacity' => '55',
                ],
                $this->styles([
                    'spacing_preset' => 'hero',
                    'max_width' => 'full',
                    'surface_style' => 'forest-gradient',
                ]),
                customId: 'services-hero'
            ),
            $this->block(
                'service_categories',
                [
                    'eyebrow' => 'Core Disciplines',
                    'heading' => 'Architectural hardscaping and structural solutions',
                    'subtitle' => 'From interlocking and retaining walls to premium outdoor living, every discipline is structured for long-term performance.',
                    'layout' => 'grid',
                    'variant' => 'editorial',
                    'tone' => 'light',
                    'show_service_preview' => true,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'services-categories'
            ),
        ];

        $blocks[] = $featureMediaId
            ? $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Build Standards',
                    'heading' => 'Precision in planning, base work, and finish quality',
                    'description' => 'We coordinate drainage, grading, structural prep, and finish materials so the final landscape performs as well as it presents.',
                    'media_id' => $featureMediaId,
                    'media_side' => 'left',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [
                        ['icon' => 'ruler', 'title' => 'Disciplined Base Preparation', 'description' => 'Stable, properly graded foundations that support long-term performance.'],
                        ['icon' => 'shield-check', 'title' => 'Licensed & Insured Delivery', 'description' => 'Accountable crews, protected sites, and professional execution from start to finish.'],
                        ['icon' => 'gem', 'title' => 'Premium Material Direction', 'description' => 'Surfaces and finishes selected for both durability and architectural character.'],
                    ],
                    'cta_text' => 'Talk Through Your Project',
                    'cta_url' => '/contact',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'services-standards'
            )
            : $this->block(
                'trust_badges',
                [
                    'eyebrow' => 'Build Standards',
                    'heading' => 'Precision in planning, base work, and finish quality',
                    'subtitle' => 'Professional discipline, premium materials, and accountable sequencing shape every project.',
                    'variant' => 'cards',
                    'tone' => 'light',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'services-standards'
            );

        $blocks[] = $this->block(
            'process_steps',
            [
                'eyebrow' => 'Project Rhythm',
                'heading' => 'A structured sequence from consultation to completion',
                'subtitle' => 'Each engagement follows a disciplined workflow so scope, site preparation, and final execution stay aligned.',
                'variant' => 'feature_rows',
                'tone' => 'cream',
            ],
            $this->styles([
                'max_width' => 'xl',
                'spacing_preset' => 'section',
                'surface_style' => 'cream-panel',
                'section_shell' => 'soft-panel',
            ]),
            customId: 'services-process'
        );

        $blocks[] = $this->block(
            'form_block',
            [
                'form_slug' => 'request-quote',
                'show_title' => false,
                'eyebrow' => 'Get Started',
                'heading' => 'Tell us about your landscape project',
                'description' => 'Share your goals, priorities, and site details so we can recommend the right construction path.',
                'variant' => 'split',
                'tone' => 'light',
                'panel_style' => 'luxury',
                'field_style' => 'luxury',
                'field_columns' => 'auto',
                'submit_text' => 'Request a Consultation',
                'show_contact_details' => true,
                'support_cta_text' => 'Call Instead',
                'support_cta_url' => '/contact',
            ],
            $this->styles([
                'max_width' => 'xl',
                'spacing_preset' => 'feature',
            ]),
            customId: 'services-contact'
        );

        return $blocks;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildLocationsHub(): array
    {
        [$heroMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'hero',
                [
                    'heading' => 'Serving Greater Toronto’s Premier Enclaves',
                    'subtitle' => 'Explore our service areas to see local landscaping coverage, project references, and city-specific construction pages.',
                    'eyebrow' => 'Service Areas',
                    'cta_primary_text' => 'Request a Consultation',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_text' => 'Explore Services',
                    'cta_secondary_url' => '/services',
                    'hero_media_id' => $heroMediaId,
                    'overlay_opacity' => '55',
                ],
                $this->styles([
                    'spacing_preset' => 'hero',
                    'max_width' => 'full',
                    'surface_style' => 'forest-gradient',
                ]),
                customId: 'locations-hero'
            ),
            $this->block(
                'city_grid',
                [
                    'eyebrow' => 'Coverage Map',
                    'heading' => 'Browse the cities we actively serve',
                    'subtitle' => 'Select your city to see available service pages, project context, and local positioning.',
                    'layout' => 'compact',
                    'tone' => 'light',
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'locations-grid'
            ),
            $this->block(
                'trust_badges',
                [
                    'eyebrow' => 'Why Homeowners Call Us',
                    'heading' => 'One team, multiple cities, consistent standards',
                    'subtitle' => 'Local pages should still feel premium. Our delivery standard stays consistent from one city to the next.',
                    'variant' => 'cards',
                    'tone' => 'cream',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_style' => 'cream-panel',
                    'section_shell' => 'soft-panel',
                ]),
                customId: 'locations-trust'
            ),
            $this->block(
                'form_block',
                [
                    'form_slug' => 'request-quote',
                    'show_title' => false,
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Planning a project in your area?',
                    'description' => 'Tell us your city, timeline, and goals so we can point you to the right service and next step.',
                    'variant' => 'split',
                    'tone' => 'light',
                    'panel_style' => 'luxury',
                    'field_style' => 'luxury',
                    'field_columns' => 'auto',
                    'submit_text' => 'Request a Consultation',
                    'show_contact_details' => true,
                    'support_cta_text' => 'Speak With Our Team',
                    'support_cta_url' => '/contact',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'locations-contact'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildPortfolioIndex(): array
    {
        [$heroMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'hero',
                [
                    'heading' => 'A portfolio of crafted outdoor environments',
                    'subtitle' => 'Browse completed landscape projects across Ontario, from interlocking driveways to premium outdoor living spaces.',
                    'eyebrow' => 'Completed Works',
                    'cta_primary_text' => 'Request a Consultation',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_text' => 'Explore Services',
                    'cta_secondary_url' => '/services',
                    'hero_media_id' => $heroMediaId,
                    'overlay_opacity' => '55',
                ],
                $this->styles([
                    'spacing_preset' => 'hero',
                    'max_width' => 'full',
                    'surface_style' => 'forest-gradient',
                ]),
                customId: 'portfolio-hero'
            ),
            $this->block(
                'portfolio_directory',
                [
                    'eyebrow' => 'Portfolio',
                    'heading' => 'Recent project highlights',
                    'subtitle' => 'Real work, real materials, and a closer look at how our landscapes come together on site.',
                    'tone' => 'light',
                    'show_filters' => true,
                    'show_featured_hero' => true,
                    'show_category_pills' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'portfolio-directory',
                dataSource: [
                    'limit' => 12,
                ]
            ),
            $this->block(
                'cta_section',
                [
                    'eyebrow' => 'Project Consultation',
                    'title' => 'Want to build something at this level?',
                    'subtitle' => 'We can help you shape a project scope, evaluate site realities, and move toward a buildable plan.',
                    'variant' => 'split',
                    'tone' => 'cream',
                    'button_text' => 'Request a Consultation',
                    'button_url' => '/contact',
                    'button_secondary_text' => 'Contact Our Team',
                    'button_secondary_url' => '/contact',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'compact',
                ]),
                customId: 'portfolio-cta'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildBlogIndex(): array
    {
        [$heroMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'hero',
                [
                    'heading' => 'Insights for planning, pricing, and long-term performance',
                    'subtitle' => 'Expert landscaping guidance, project education, and cost context for Ontario homeowners.',
                    'eyebrow' => 'Knowledge Library',
                    'cta_primary_text' => 'Request a Consultation',
                    'cta_primary_url' => '/contact',
                    'cta_secondary_text' => 'Explore Services',
                    'cta_secondary_url' => '/services',
                    'hero_media_id' => $heroMediaId,
                    'overlay_opacity' => '55',
                ],
                $this->styles([
                    'spacing_preset' => 'hero',
                    'max_width' => 'full',
                    'surface_style' => 'forest-gradient',
                ]),
                customId: 'blog-hero'
            ),
            $this->block(
                'blog_directory',
                [
                    'eyebrow' => 'Latest Articles',
                    'heading' => 'Practical landscaping advice and project insight',
                    'subtitle' => 'From budgeting and permitting to material selection and maintenance, find guidance that helps you plan with clarity.',
                    'tone' => 'light',
                    'show_featured_hero' => true,
                    'show_category_tabs' => true,
                    'empty_title' => 'Articles coming soon',
                    'empty_description' => 'We are preparing expert articles, cost guides, and planning resources for homeowners and design-minded clients.',
                    'empty_button_text' => 'Explore Services',
                    'empty_button_url' => '/services',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'blog-directory',
                dataSource: [
                    'limit' => 12,
                ]
            ),
            $this->block(
                'cta_section',
                [
                    'eyebrow' => 'Need Specific Advice?',
                    'title' => 'Talk through your project with our team.',
                    'subtitle' => 'If you are weighing options, timelines, or materials, we can help you move from research to a practical next step.',
                    'variant' => 'panel',
                    'tone' => 'cream',
                    'button_text' => 'Request a Consultation',
                    'button_url' => '/contact',
                    'button_secondary_text' => 'Contact Us',
                    'button_secondary_url' => '/contact',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'compact',
                ]),
                customId: 'blog-cta'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function scaffoldPortfolioCategories(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedPortfolioCategories() as $category) {
            $existingBlocks = PageBlock::forPage('portfolio_category', $category->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildPortfolioCategory($category);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('portfolio_category', $category->id);
            }

            BlockBuilderService::saveUnifiedBlocks('portfolio_category', $category->id, $blocks);

            $results[] = [
                'id' => $category->id,
                'slug' => $category->slug,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function scaffoldServiceCategories(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedServiceCategories() as $category) {
            $existingBlocks = PageBlock::forPage('service_category', $category->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $category->id,
                    'slug' => $category->slug_final,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildServiceCategory($category);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('service_category', $category->id);
            }

            BlockBuilderService::saveUnifiedBlocks('service_category', $category->id, $blocks);

            $results[] = [
                'id' => $category->id,
                'slug' => $category->slug_final,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function scaffoldBlogCategories(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedBlogCategories() as $category) {
            $existingBlocks = PageBlock::forPage('blog_category', $category->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildBlogCategory($category);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('blog_category', $category->id);
            }

            BlockBuilderService::saveUnifiedBlocks('blog_category', $category->id, $blocks);

            $results[] = [
                'id' => $category->id,
                'slug' => $category->slug,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    /**
     * @return array{0:?int,1:?int}
     */
    private function showcaseMediaPair(): array
    {
        $ids = PortfolioProject::query()
            ->where('status', 'published')
            ->whereNotNull('hero_media_id')
            ->orderByDesc('is_featured')
            ->orderByDesc('completion_date')
            ->orderBy('sort_order')
            ->limit(4)
            ->pluck('hero_media_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        return [$ids[0] ?? null, $ids[1] ?? ($ids[0] ?? null)];
    }

    private function publishedPortfolioCategories(): Collection
    {
        return PortfolioCategory::query()
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->get();
    }

    private function publishedServiceCategories(): Collection
    {
        return ServiceCategory::query()
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->get();
    }

    private function publishedBlogCategories(): Collection
    {
        return BlogCategory::query()
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->get();
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
        array $dataSource = [],
    ): array {
        return [
            'block_type' => $type,
            'category' => BlockBuilderService::typeConfig($type)['category'] ?? 'content',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => $content,
            'data_source' => $dataSource !== [] ? $dataSource : null,
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
}
