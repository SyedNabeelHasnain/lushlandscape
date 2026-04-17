<?php

$file = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php';
$content = file_get_contents($file);

$scaffoldTaxonomyPages = <<<'PHP'
    public function scaffoldTaxonomyPages(bool $replace = false): array
    {
        return [
            'service_categories' => $this->scaffoldServiceCategories($replace),
            'services' => $this->scaffoldServices($replace),
            'cities' => $this->scaffoldCities($replace),
            'service_cities' => $this->scaffoldServiceCities($replace),
            'portfolio_categories' => $this->scaffoldPortfolioCategories($replace),
            'blog_categories' => $this->scaffoldBlogCategories($replace),
        ];
    }
PHP;

$content = preg_replace('/    public function scaffoldTaxonomyPages\(bool \$replace = false\): array\n    \{.*?\n    \}/s', $scaffoldTaxonomyPages, $content);

$scaffoldCityMethods = <<<'PHP'
    private function scaffoldCities(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedCities() as $city) {
            $existingBlocks = \App\Models\PageBlock::forPage('city', $city->id)->count();

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
                \App\Services\BlockBuilderService::deleteAllBlocksForPage('city', $city->id);
            }

            \App\Services\BlockBuilderService::saveUnifiedBlocks('city', $city->id, $blocks);

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
            $existingBlocks = \App\Models\PageBlock::forPage('service_city_page', $page->id)->count();

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
                \App\Services\BlockBuilderService::deleteAllBlocksForPage('service_city_page', $page->id);
            }

            \App\Services\BlockBuilderService::saveUnifiedBlocks('service_city_page', $page->id, $blocks);

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

    private function publishedCities(): \Illuminate\Support\Collection
    {
        try {
            return \App\Models\City::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function activeServiceCities(): \Illuminate\Support\Collection
    {
        try {
            return \App\Models\ServiceCityPage::query()->where('is_active', true)->with(['city', 'service'])->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
PHP;

$content = preg_replace('/    private function scaffoldServiceCategories/s', $scaffoldCityMethods."\n\n    private function scaffoldServiceCategories", $content);

$buildLocationsHub = <<<'PHP'
    private function buildLocationsHub(): array
    {
        [$heroMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Serving Greater Toronto’s Premier Enclaves',
                    'subheadline' => 'Explore our service areas to see local landscaping coverage, project references, and city-specific construction pages.',
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
                    'cta_url' => '/contact',
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
                    'heading' => 'Start your project in the GTA',
                    'editorial_copy' => 'We can help define scope, material direction, and the best path from consultation to construction in your specific area.',
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
                customId: 'locations-contact'
            ),
        ];
    }
PHP;

$content = preg_replace('/    private function buildLocationsHub\(\): array\n    \{.*?\n    \}/s', $buildLocationsHub, $content);

$buildCityAndServiceCity = <<<'PHP'
    public function buildCity(\App\Models\City $city): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Landscape Construction in ' . $city->name,
                    'subheadline' => 'Premium outdoor living, architectural hardscaping, and structural solutions for ' . $city->name . ' properties.',
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
                    'eyebrow' => $city->name . ' Overview',
                    'heading' => 'Engineered Outdoor Spaces in ' . $city->name,
                    'description' => 'We deliver structured, high-end landscape construction across ' . $city->name . ', respecting local grading constraints and architectural intent.',
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
                customId: 'city-overview'
            ),
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Available Services',
                    'heading' => 'Our ' . $city->name . ' Services',
                    'subtitle' => 'Select a specific service below to see our approach to delivery in ' . $city->name . '.',
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
                    'heading' => 'Discuss Your ' . $city->name . ' Project',
                    'editorial_copy' => 'Schedule a consultation to discuss your specific site requirements, scope, and execution timelines in ' . $city->name . '.',
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
                customId: 'city-contact'
            ),
        ];
    }

    public function buildServiceCity(\App\Models\ServiceCityPage $page): array
    {
        $cityName = $page->city ? $page->city->name : 'Your Area';
        $serviceName = $page->service ? $page->service->name : 'Landscaping';
        
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $page->h1 ?: $serviceName . ' in ' . $cityName,
                    'subheadline' => $page->local_intro ?: 'Precision installation and structural integrity for premium outdoor spaces in ' . $cityName . '.',
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
                    'heading' => 'Premium ' . $serviceName . ' in ' . $cityName,
                    'description' => 'We apply rigorous construction standards to ' . strtolower($serviceName) . ' projects in ' . $cityName . ', ensuring every detail meets architectural expectations.',
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
                customId: 'service-city-overview'
            ),
            $this->block(
                'authority_grid',
                [
                    'eyebrow' => 'Value & Scope',
                    'heading' => 'Why Choose Our ' . $serviceName . ' in ' . $cityName,
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
                    'heading' => 'Other Services in ' . $cityName,
                    'subtitle' => 'Discover other specialized services that complement your ' . $serviceName . ' project.',
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
                    'heading' => 'Discuss Your ' . $cityName . ' Project',
                    'editorial_copy' => 'Schedule a consultation to discuss your specific site requirements, scope, and execution timelines in ' . $cityName . '.',
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
                customId: 'service-city-contact'
            ),
        ];
    }
PHP;

$content = preg_replace('/}\s*$/s', "\n".$buildCityAndServiceCity."\n}\n", $content);

file_put_contents($file, $content);
echo "Replaced successfully using PHP script.\n";
