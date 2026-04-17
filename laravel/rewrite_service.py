import re

file_path = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php'
with open(file_path, 'r') as f:
    content = f.read()

# Replace buildServicesHub
new_buildServicesHub = """    private function buildServicesHub(): array
    {
        [$heroMediaId, $featureMediaId] = $this->showcaseMediaPair();

        $blocks = [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Architectural Hardscaping & Outdoor Construction',
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
                    'cta_url' => '/contact',
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
                customId: 'services-contact'
            )
        ];

        return $blocks;
    }"""

content = re.sub(r'    private function buildServicesHub\(\): array\n    \{.*?(?=    /\*\*|\Z)', new_buildServicesHub + "\n\n", content, flags=re.DOTALL)

# Replace buildServiceCategory
new_buildServiceCategory = r"""    public function buildServiceCategory(ServiceCategory $category): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $category->name,
                    'subheadline' => $category->short_description ?: 'A focused collection of premium landscaping services delivered with structural discipline and finish quality.',
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
                    'heading' => 'Engineered ' . $category->name . ' Solutions',
                    'description' => $category->long_description ?: 'Our ' . $category->name . ' services are executed with precise base preparation and premium material selection to ensure enduring structural integrity.',
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
                customId: 'service-category-contact'
            ),
        ];
    }"""

content = re.sub(r'    public function buildServiceCategory\(ServiceCategory \$category\): array\n    \{.*?(?=    /\*\*|\Z)', new_buildServiceCategory + "\n\n", content, flags=re.DOTALL)

# Add buildServiceDetail
new_buildServiceDetail = r"""    public function buildServiceDetail(Service $service): array
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
                    'heading' => 'Premium ' . $service->name . ' Execution',
                    'description' => $service->long_description ?: 'We apply rigorous construction standards to ' . strtolower($service->name) . ', ensuring every detail meets architectural and engineering expectations.',
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
                customId: 'service-overview'
            ),
            $this->block(
                'authority_grid',
                [
                    'eyebrow' => 'Value & Scope',
                    'heading' => 'Why Choose Our ' . $service->name . ' Services',
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
                    'subtitle' => 'Discover other specialized services that complement your ' . $service->name . ' project.',
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
                    'heading' => 'Discuss Your ' . $service->name . ' Project',
                    'editorial_copy' => 'Schedule a consultation to discuss your specific site requirements, scope, and execution timelines.',
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
                customId: 'service-contact'
            ),
        ];
    }"""

content = re.sub(r'(?=\}\s*$)', "\n" + new_buildServiceDetail + "\n", content)

with open(file_path, 'w') as f:
    f.write(content)
print("Updated successfully")
