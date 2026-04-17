<?php
$file = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php';
$content = file_get_contents($file);

$buildServiceCategory = <<<'PHP'
    public function buildServiceCategory(ServiceCategory $category): array
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
    }
PHP;

$buildServiceDetail = <<<'PHP'
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
    }
PHP;

$content = preg_replace('/    public function buildServiceCategory\(ServiceCategory \$category\): array\n    \{.*?(?=    \/\*\*|\z)/s', $buildServiceCategory . "\n\n", $content);

$content = preg_replace('/}\s*$/s', "\n" . $buildServiceDetail . "\n}\n", $content);

// And we need to add use App\Models\Service; if it's missing
if (strpos($content, 'use App\Models\Service;') === false) {
    $content = str_replace("use App\Models\ServiceCategory;", "use App\Models\ServiceCategory;\nuse App\Models\Service;", $content);
}

file_put_contents($file, $content);
echo "Replaced ServiceCategory and added ServiceDetail.\n";
