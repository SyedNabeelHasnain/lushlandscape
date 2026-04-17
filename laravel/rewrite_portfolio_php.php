<?php
$file = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php';
$content = file_get_contents($file);

$scaffoldTaxonomies = <<<'PHP'
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
        ];
    }
PHP;

$content = preg_replace('/    public function scaffoldTaxonomyPages\(bool \$replace = false\): array\n    \{.*?\n    \}/s', $scaffoldTaxonomies, $content);

$scaffoldPortfolioMethods = <<<'PHP'
    private function scaffoldPortfolioCategories(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedPortfolioCategories() as $category) {
            $existingBlocks = \App\Models\PageBlock::forPage('portfolio_category', $category->id)->count();

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
                \App\Services\BlockBuilderService::deleteAllBlocksForPage('portfolio_category', $category->id);
            }

            \App\Services\BlockBuilderService::saveUnifiedBlocks('portfolio_category', $category->id, $blocks);

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
            $existingBlocks = \App\Models\PageBlock::forPage('portfolio_project', $project->id)->count();

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
                \App\Services\BlockBuilderService::deleteAllBlocksForPage('portfolio_project', $project->id);
            }

            \App\Services\BlockBuilderService::saveUnifiedBlocks('portfolio_project', $project->id, $blocks);

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

    private function publishedPortfolioCategories(): \Illuminate\Support\Collection
    {
        try {
            return \App\Models\PortfolioCategory::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function publishedPortfolioProjects(): \Illuminate\Support\Collection
    {
        try {
            return \App\Models\PortfolioProject::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
PHP;

$content = preg_replace('/    private function scaffoldPortfolioCategories\(bool \$replace\): array\n    \{\n        \$results = \[\];\n\n        foreach \(\$this->publishedPortfolioCategories\(\) as \$category\) \{\n.*?\n        \}\n\n        return \$results;\n    \}\n/s', '', $content);
$content = preg_replace('/    private function publishedPortfolioCategories\(\): Collection\n    \{\n.*?\n    \}\n/s', '', $content);
$content = preg_replace('/    private function scaffoldPortfolioProjects\(bool \$replace\): array\n    \{\n        \$results = \[\];\n\n        foreach \(\$this->publishedPortfolioProjects\(\) as \$project\) \{\n.*?\n        \}\n\n        return \$results;\n    \}\n/s', '', $content);
$content = preg_replace('/    private function publishedPortfolioCategories\(\): \\\\Illuminate\\\\Support\\\\Collection\n    \{\n.*?\n    \}\n/s', '', $content);
$content = preg_replace('/    private function publishedPortfolioProjects\(\): \\\\Illuminate\\\\Support\\\\Collection\n    \{\n.*?\n    \}\n/s', '', $content);
$content = preg_replace('/    private function scaffoldBlogCategories/s', $scaffoldPortfolioMethods . "\n\n    private function scaffoldBlogCategories", $content);

$buildPortfolioIndex = <<<'PHP'
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
                    'cta_url' => '/contact',
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
                customId: 'portfolio-contact'
            ),
        ];
    }
PHP;

$content = preg_replace('/    private function buildPortfolioIndex\(\): array\n    \{.*?\n    \}/s', $buildPortfolioIndex, $content);

$buildPortfolioCategory = <<<'PHP'
    public function buildPortfolioCategory(\App\Models\PortfolioCategory $category): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $category->name . ' Projects',
                    'subheadline' => $category->short_description ?: 'Explore our completed ' . strtolower($category->name) . ' projects, executed with premium materials and structural precision.',
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
                    'heading' => 'Recent ' . $category->name . ' Builds',
                    'subtitle' => 'Review our approach to ' . strtolower($category->name) . ' construction across various property types.',
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
                    'heading' => 'Discuss Your ' . $category->name . ' Project',
                    'editorial_copy' => 'Connect with our team to discuss your specific requirements, material options, and execution timelines.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
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
                customId: 'portfolio-category-contact'
            ),
        ];
    }
PHP;

$content = preg_replace('/    public function buildPortfolioCategory\(PortfolioCategory \$category\): array\n    \{.*?\n    \}/s', $buildPortfolioCategory, $content);

$buildPortfolioProject = <<<'PHP'
    public function buildPortfolioProject(\App\Models\PortfolioProject $project): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $project->title,
                    'subheadline' => $project->description ?: 'A premium landscaping build showcasing structural precision and high-end finish quality.',
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
                    'cta_url' => '/contact',
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
                    'subtitle' => 'Explore the specific landscaping and construction services utilized to deliver this project.',
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
                customId: 'project-contact'
            ),
        ];
    }
PHP;

$content = preg_replace('/    public function buildPortfolioProject\(\\\\App\\\\Models\\\\PortfolioProject \$project\): array\n    \{.*?\n    \}/s', '', $content);
$content = preg_replace('/    public function buildPortfolioProject\(PortfolioProject \$project\): array\n    \{.*?\n    \}/s', '', $content);
$content = preg_replace('/}\s*$/s', "\n" . $buildPortfolioProject . "\n}\n", $content);

file_put_contents($file, $content);
echo "Replaced Portfolio methods successfully.\n";
