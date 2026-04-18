<?php

namespace App\Console\Services;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\PageBlock;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
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
            'services' => $this->scaffoldServices($replace),
            'cities' => $this->scaffoldCities($replace),
            'service_cities' => $this->scaffoldServiceCities($replace),
            'portfolio_categories' => $this->scaffoldPortfolioCategories($replace),
            'portfolio_projects' => $this->scaffoldPortfolioProjects($replace),
            'blog_categories' => $this->scaffoldBlogCategories($replace),
            'blog_posts' => $this->scaffoldBlogPosts($replace),
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
            'contact' => $this->buildContact(),
            'consultation' => $this->buildConsultation(),
            'faqs-index' => $this->buildFaqIndex(),
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
                'parallax_media_band',
                [
                    'heading' => $category->name.' Projects',
                    'subheadline' => $category->short_description ?: 'Explore our completed '.strtolower($category->name).' projects, executed with premium materials and structural precision.',
                    'media_id' => $category->image_id,
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
                customId: 'portfolio-category-hero'
            ),
            $this->block(
                'portfolio_gallery',
                [
                    'eyebrow' => $category->name,
                    'heading' => 'Recent '.$category->name.' Builds',
                    'subtitle' => 'Review our approach to '.strtolower($category->name).' construction across various property types.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'editorial',
                    'tone' => 'cream',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'portfolio-category-gallery'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Discuss Your '.$category->name.' Project',
                    'editorial_copy' => 'Connect with our team to discuss your specific requirements, material options, and execution timelines.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                'form_slug' => 'consultation',
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
                customId: 'portfolio-category-contact'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildServiceCategory(ServiceCategory $category): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $category->name,
                    'subheadline' => $category->short_description ?: 'A focused collection of premium professional services delivered with structural discipline and finish quality.',
                    'media_id' => $category->hero_media_id,
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
                customId: 'service-category-hero'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Category Overview',
                    'heading' => 'Engineered '.$category->name.' Solutions',
                    'description' => $category->long_description ?: 'Our '.$category->name.' services are executed with precise base preparation and premium material selection to ensure enduring structural integrity.',
                    'media_id' => null,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Request a Consultation',
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                ]),
                customId: 'service-category-overview'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Available Services',
                    'heading' => 'Explore '.$category->name,
                    'subtitle' => 'Select the exact service that matches your project scope, property conditions, and desired finish level.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => false,
                    'card_cta_label' => 'View Service',
                    'tone' => 'cream',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'service-category-grid'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Planning',
                    'heading' => 'Ready to plan your '.$category->name.' project?',
                    'editorial_copy' => 'We can help define scope, material direction, and the best path from consultation to construction.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'service-category-contact'
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
                'parallax_media_band',
                [
                    'heading' => 'Architectural Engineering & Outdoor Construction',
                    'subheadline' => 'Landscape construction for architects, designers, and discerning homeowners across the Greater Toronto Area.',
                    'media_id' => $heroMediaId,
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
                    'spacing_preset' => 'section',
                ]),
                customId: 'services-categories'
            ),
            $this->block(
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
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'services-standards'
            ),
            $this->block(
                'process_steps',
                [
                    'eyebrow' => 'Project Rhythm',
                    'heading' => 'A structured sequence from consultation to completion',
                    'subtitle' => 'Each engagement follows a disciplined workflow so scope, site preparation, and final execution stay aligned.',
                    'variant' => 'premium-stack',
                    'tone' => 'cream',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'services-process'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Get Started',
                    'heading' => 'Tell us about your landscape project',
                    'editorial_copy' => 'Share your goals, priorities, and site details so we can recommend the right construction path.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'services-contact'
            ),
        ];

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
                'parallax_media_band',
                [
                    'heading' => 'Serving Greater Toronto’s Premier Enclaves',
                    'subheadline' => 'Explore our service areas to see local professional coverage, project references, and city-specific construction pages.',
                    'media_id' => $heroMediaId,
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
                customId: 'locations-hero'
            ),
            $this->block(
                'service_area_enclave',
                [
                    'eyebrow' => 'Coverage Map',
                    'heading' => 'Browse the cities we actively serve',
                    'support_copy' => 'Select your city to see available service pages, project context, and local positioning.',
                    'presentation_mode' => 'tabbed-enclave',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'locations-grid'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Local Expertise',
                    'heading' => 'Navigating Municipal Standards',
                    'description' => 'From ravine lot restrictions in Toronto to executive estate grading in Oakville, we understand the municipal requirements that govern premium landscape construction.',
                    'media_id' => null,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Request a Consultation',
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'locations-expertise'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Planning',
                    'heading' => 'Start your project in the Region',
                    'editorial_copy' => 'We can help define scope, material direction, and the best path from consultation to construction in your specific area.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'locations-contact'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildPortfolioIndex(): array
    {
        [$heroMediaId, $featureMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Completed Landscape Projects',
                    'subheadline' => 'Explore our portfolio of premium interlocking, architectural hardscaping, and structured outdoor living spaces.',
                    'media_id' => $heroMediaId,
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
                customId: 'portfolio-hero'
            ),
            $this->block(
                'portfolio_gallery',
                [
                    'eyebrow' => 'Selected Work',
                    'heading' => 'Explore Our Recent Builds',
                    'subtitle' => 'Browse projects by category, location, or service type to see our execution standards.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'editorial',
                    'tone' => 'light',
                    'show_category_nav' => true,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'portfolio-gallery'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Execution Standard',
                    'heading' => 'Built for Endurance',
                    'description' => 'We don’t just build for opening day. Every project in our portfolio represents rigorous base preparation, precise material selection, and long-term structural integrity.',
                    'media_id' => $featureMediaId,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Discuss Your Project',
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                ]),
                customId: 'portfolio-standards'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Start Your Landscape Project',
                    'editorial_copy' => 'Connect with our team to discuss your property, design goals, and execution timelines.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'portfolio-contact'
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
                'parallax_media_band',
                [
                    'heading' => 'Professional Knowledge & Advice',
                    'subheadline' => 'Expert tips, cost guides, and project inspiration for Our Region homeowners.',
                    'media_id' => $heroMediaId,
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
                customId: 'blog-hero'
            ),
            $this->block(
                'blog_directory',
                [
                    'eyebrow' => 'Editorial & Advice',
                    'heading' => 'Explore the Lush Landscape Blog',
                    'subtitle' => 'Browse our latest articles to help you plan, budget, and execute your outdoor space.',
                    'tone' => 'light',
                    'show_featured_hero' => true,
                    'show_category_tabs' => true,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'blog-directory'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Next Steps',
                    'heading' => 'Ready to Start Planning?',
                    'editorial_copy' => 'If our articles have inspired you, connect with our team to discuss how we can bring those ideas to your property.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'blog-contact'
            ),
        ];
    }

    public function buildBlogCategory(BlogCategory $category): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $category->name.' Articles',
                    'subheadline' => $category->short_description ?: 'Explore our expert advice and guidance regarding '.strtolower($category->name).'.',
                    'media_id' => $category->image_id,
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
                customId: 'blog-category-hero'
            ),
            $this->block(
                'blog_directory',
                [
                    'eyebrow' => $category->name,
                    'heading' => 'Articles about '.$category->name,
                    'subtitle' => 'Browse our latest insights and project advice for '.strtolower($category->name).'.',
                    'tone' => 'cream',
                    'show_featured_hero' => false,
                    'show_category_tabs' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'blog-category-directory'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Next Steps',
                    'heading' => 'Need advice on '.strtolower($category->name).'?',
                    'editorial_copy' => 'Connect with our team to discuss your specific requirements and receive expert guidance tailored to your property.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'blog-category-contact'
            ),
        ];
    }

    public function buildBlogPost(BlogPost $post): array
    {
        return [
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Related Services',
                    'heading' => 'Relevant Services',
                    'subtitle' => 'Explore professional services related to this article.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => false,
                    'card_cta_label' => 'View Service',
                    'tone' => 'cream',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'post-services'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Apply this to your property',
                    'editorial_copy' => 'If this article sparked an idea, connect with our team to discuss how we can execute it professionally on your property.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'post-contact'
            ),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */

    /**
     * @return array<int, array<string, mixed>>
     */

    /**
     * @return array<int, array<string, mixed>>
     */
    private function scaffoldServices(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedServices() as $service) {
            $existingBlocks = PageBlock::forPage('service', $service->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $service->id,
                    'slug' => $service->slug_final,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildServiceDetail($service);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('service', $service->id);
            }

            BlockBuilderService::saveUnifiedBlocks('service', $service->id, $blocks);

            $results[] = [
                'id' => $service->id,
                'slug' => $service->slug_final,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    private function scaffoldCities(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedCities() as $city) {
            $existingBlocks = PageBlock::forPage('city', $city->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $city->id,
                    'slug' => $city->slug_final,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildCity($city);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('city', $city->id);
            }

            BlockBuilderService::saveUnifiedBlocks('city', $city->id, $blocks);

            $results[] = [
                'id' => $city->id,
                'slug' => $city->slug_final,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    private function scaffoldServiceCities(bool $replace): array
    {
        $results = [];

        foreach ($this->activeServiceCities() as $page) {
            $existingBlocks = PageBlock::forPage('service_city_page', $page->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $page->id,
                    'slug' => $page->slug_final,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildServiceCity($page);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('service_city_page', $page->id);
            }

            BlockBuilderService::saveUnifiedBlocks('service_city_page', $page->id, $blocks);

            $results[] = [
                'id' => $page->id,
                'slug' => $page->slug_final,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    private function publishedCities(): Collection
    {
        try {
            return City::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function activeServiceCities(): Collection
    {
        try {
            return ServiceCityPage::query()->where('is_active', true)->with(['city', 'service'])->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

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

    private function scaffoldPortfolioProjects(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedPortfolioProjects() as $project) {
            $existingBlocks = PageBlock::forPage('portfolio_project', $project->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $project->id,
                    'slug' => $project->slug,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildPortfolioProject($project);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('portfolio_project', $project->id);
            }

            BlockBuilderService::saveUnifiedBlocks('portfolio_project', $project->id, $blocks);

            $results[] = [
                'id' => $project->id,
                'slug' => $project->slug,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    private function publishedPortfolioCategories(): Collection
    {
        try {
            return PortfolioCategory::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function publishedPortfolioProjects(): Collection
    {
        try {
            return PortfolioProject::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

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

    private function scaffoldBlogPosts(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedBlogPosts() as $post) {
            $existingBlocks = PageBlock::forPage('blog_post', $post->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            $blocks = $this->buildBlogPost($post);

            if ($replace) {
                BlockBuilderService::deleteAllBlocksForPage('blog_post', $post->id);
            }

            BlockBuilderService::saveUnifiedBlocks('blog_post', $post->id, $blocks);

            $results[] = [
                'id' => $post->id,
                'slug' => $post->slug,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    private function publishedBlogCategories(): Collection
    {
        try {
            return BlogCategory::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function publishedBlogPosts(): Collection
    {
        try {
            return BlogPost::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function showcaseMediaPair(): array
    {
        try {
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
        } catch (\Exception $e) {
            return [null, null];
        }
    }

    private function publishedServices(): Collection
    {
        try {
            return Service::query()
                ->where('status', 'published')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function publishedServiceCategories(): Collection
    {
        return ServiceCategory::query()
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

    public function buildServiceDetail(Service $service): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $service->name,
                    'subheadline' => $service->service_summary ?: 'Precision installation and structural integrity for premium outdoor spaces.',
                    'media_id' => $service->hero_media_id,
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
                customId: 'service-hero'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Service Overview',
                    'heading' => 'Premium '.$service->name.' Execution',
                    'description' => $service->long_description ?: 'We apply rigorous construction standards to '.strtolower($service->name).', ensuring every detail meets architectural and engineering expectations.',
                    'media_id' => null,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Request a Consultation',
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                ]),
                customId: 'service-overview'
            ),
            $this->block(
                'authority_grid',
                [
                    'eyebrow' => 'Value & Scope',
                    'heading' => 'Why Choose Our '.$service->name.' Services',
                    'introduction' => 'We focus on durability, material quality, and precise installation.',
                    'card_skin' => 'elevated',
                    'items' => [
                        ['icon' => 'ruler', 'title' => 'Precision Engineering', 'description' => 'Built to exact specifications and grading requirements.'],
                        ['icon' => 'gem', 'title' => 'Premium Materials', 'description' => 'Sourced from the finest stone and hardscaping suppliers.'],
                        ['icon' => 'shield-check', 'title' => 'Long-Term Durability', 'description' => 'Constructed to withstand harsh seasonal freeze-thaw cycles.'],
                    ],
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                    'surface_preset' => 'cream',
                ]),
                customId: 'service-authority'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Related Services',
                    'heading' => 'Explore More Services',
                    'subtitle' => 'Discover other specialized services that complement your '.$service->name.' project.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => false,
                    'card_cta_label' => 'View Service',
                    'tone' => 'light',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'service-related'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Discuss Your '.$service->name.' Project',
                    'editorial_copy' => 'Schedule a consultation to discuss your specific site requirements, scope, and execution timelines.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'service-contact'
            ),
        ];
    }

    public function buildCity(City $city): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Landscape Construction in '.$city->name,
                    'subheadline' => 'Premium outdoor living, architectural hardscaping, and structural solutions for '.$city->name.' properties.',
                    'media_id' => $city->hero_media_id,
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
                customId: 'city-hero'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => $city->name.' Overview',
                    'heading' => 'Engineered Outdoor Spaces in '.$city->name,
                    'description' => 'We deliver structured, high-end landscape construction across '.$city->name.', respecting local grading constraints and architectural intent.',
                    'media_id' => null,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Request a Consultation',
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                ]),
                customId: 'city-overview'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Available Services',
                    'heading' => 'Our '.$city->name.' Services',
                    'subtitle' => 'Select a specific service below to see our approach to delivery in '.$city->name.'.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => false,
                    'card_cta_label' => 'View Service',
                    'tone' => 'cream',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'city-services'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Discuss Your '.$city->name.' Project',
                    'editorial_copy' => 'Schedule a consultation to discuss your specific site requirements, scope, and execution timelines in '.$city->name.'.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'city-contact'
            ),
        ];
    }

    public function buildServiceCity(ServiceCityPage $page): array
    {
        $cityName = $page->city ? $page->city->name : 'Your Area';
        $serviceName = $page->service ? $page->service->name : 'Professional';

        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $page->h1 ?: $serviceName.' in '.$cityName,
                    'subheadline' => $page->local_intro ?: 'Precision installation and structural integrity for premium outdoor spaces in '.$cityName.'.',
                    'media_id' => $page->hero_media_id,
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
                customId: 'service-city-hero'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Local Service Overview',
                    'heading' => 'Premium '.$serviceName.' in '.$cityName,
                    'description' => 'We apply rigorous construction standards to '.strtolower($serviceName).' projects in '.$cityName.', ensuring every detail meets architectural expectations.',
                    'media_id' => null,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Request a Consultation',
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                ]),
                customId: 'service-city-overview'
            ),
            $this->block(
                'authority_grid',
                [
                    'eyebrow' => 'Value & Scope',
                    'heading' => 'Why Choose Our '.$serviceName.' in '.$cityName,
                    'introduction' => 'We focus on durability, material quality, and precise installation for local properties.',
                    'card_skin' => 'elevated',
                    'items' => [
                        ['icon' => 'ruler', 'title' => 'Precision Engineering', 'description' => 'Built to exact specifications and grading requirements.'],
                        ['icon' => 'gem', 'title' => 'Premium Materials', 'description' => 'Sourced from the finest stone and hardscaping suppliers.'],
                        ['icon' => 'shield-check', 'title' => 'Long-Term Durability', 'description' => 'Constructed to withstand harsh seasonal freeze-thaw cycles.'],
                    ],
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                    'surface_preset' => 'cream',
                ]),
                customId: 'service-city-authority'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Related Services',
                    'heading' => 'Other Services in '.$cityName,
                    'subtitle' => 'Discover other specialized services that complement your '.$serviceName.' project.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => false,
                    'card_cta_label' => 'View Service',
                    'tone' => 'light',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'service-city-related'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Discuss Your '.$cityName.' Project',
                    'editorial_copy' => 'Schedule a consultation to discuss your specific site requirements, scope, and execution timelines in '.$cityName.'.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'service-city-contact'
            ),
        ];
    }

    public function buildPortfolioProject(PortfolioProject $project): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $project->title,
                    'subheadline' => $project->description ?: 'A premium professional build showcasing structural precision and high-end finish quality.',
                    'media_id' => $project->hero_media_id,
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
                customId: 'project-hero'
            ),
            $this->block(
                'editorial_split_feature',
                [
                    'eyebrow' => 'Project Overview',
                    'heading' => 'Scope & Execution',
                    'description' => $project->body ?: 'This project required precise site preparation, premium material sourcing, and disciplined execution to meet the client\'s architectural expectations.',
                    'media_id' => null,
                    'media_side' => 'right',
                    'media_ratio' => '4:5',
                    'tone' => 'light',
                    'ornament_style' => 'oval',
                    'feature_layout' => 'stacked',
                    'features' => [],
                    'cta_text' => 'Request a Consultation',
                    'cta_url' => '/consultation',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                ]),
                customId: 'project-overview'
            ),
            $this->block(
                'portfolio_gallery',
                [
                    'eyebrow' => 'Media Gallery',
                    'heading' => 'Project Photography',
                    'subtitle' => 'View detailed shots of the finished installation, highlighting our attention to structural integrity and finish quality.',
                    'layout' => 'masonry',
                    'columns' => '3',
                    'variant' => 'minimal',
                    'tone' => 'cream',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'feature',
                    'surface_preset' => 'cream',
                ]),
                customId: 'project-gallery'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Related Services',
                    'heading' => 'Services Used in This Build',
                    'subtitle' => 'Explore the specific professional and construction services utilized to deliver this project.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => false,
                    'card_cta_label' => 'View Service',
                    'tone' => 'light',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'project-services'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Looking for similar results?',
                    'editorial_copy' => 'Connect with our team to discuss your property, design goals, and execution timelines for your next project.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'project-contact'
            ),
        ];
    }

    private function buildContact(): array
    {
        return [
            $this->block(
                'faq_section',
                [
                    'heading' => 'Common Inquiries',
                    'subtitle' => 'Before we connect, you might find these answers helpful.',
                    'style' => 'list',
                ],
                $this->styles([
                    'spacing_preset' => 'section',
                    'max_width' => 'xl',
                    'surface_preset' => 'cream',
                ]),
                customId: 'contact-faqs',
                dataSource: [
                    'limit' => 4,
                ]
            ),
        ];
    }

    private function buildConsultation(): array
    {
        return [
            $this->block(
                'process_steps',
                [
                    'eyebrow' => 'Our Approach',
                    'heading' => 'What to Expect',
                    'subtitle' => 'A clear, professional process from consultation to project completion.',
                    'variant' => 'numbered',
                    'tone' => 'cream',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'consultation-process'
            ),
        ];
    }

    private function buildFaqIndex(): array
    {
        [$heroMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Frequently Asked Questions',
                    'subheadline' => 'Clear answers regarding our services, processes, and what to expect when working with Lush Professional.',
                    'media_id' => $heroMediaId,
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
                customId: 'faq-hero'
            ),
            $this->block(
                'faq_directory',
                [
                    'eyebrow' => 'Help & Support',
                    'heading' => 'Find the answers you need',
                    'subtitle' => 'Browse our FAQ categories below or use the search to find specific information.',
                    'tone' => 'light',
                ],
                $this->styles([
                    'max_width' => 'full',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'faq-directory'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Still Have Questions?',
                    'heading' => 'Talk to our team directly',
                    'editorial_copy' => 'If you couldn\'t find the answer you were looking for, or if you\'re ready to start discussing your specific property, reach out to us.',
                    'trust_lines' => 'Clear communication, Expert advice, Timely responses',
                    'media_id' => null,
                    'form_slug' => 'consultation',
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
                customId: 'faq-contact'
            ),
        ];
    }
}
