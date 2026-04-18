<?php

/**
 * Unified Block Registry — single source of truth for all page blocks.
 *
 * Replaces both page_builder.php (sections) and content_blocks.php (content blocks).
 *
 * Block Categories:
 *   • data          — Dynamic blocks that pull from database (services, testimonials, etc.)
 *   • content       — Static content blocks (heading, paragraph, rich text, etc.)
 *   • layout        — Structural blocks (columns, tabs, accordions, etc.)
 *   • media         — Visual blocks (image, video, gallery, slider, etc.)
 *   • interactive   — Engagement blocks (CTA, form, map, counter, etc.)
 *
 * Each block type defines:
 *   - label, icon, category
 *   - content_fields: static content fields
 *   - data_source: dynamic data configuration (for data blocks)
 *   - style_fields: styling options
 *   - defaults
 */

return [

    'section_map' => [
        'hero' => 'hero',
        'hero_luxury' => 'hero_luxury',
        'architectural_standard' => 'architectural_standard',
        'architectural_services' => 'architectural_services',
        'parallax_banner_luxury' => 'parallax_banner_luxury',
        'portfolio_slider' => 'portfolio_slider',
        'portfolio_masonry_grid_luxury' => 'portfolio_masonry_grid_luxury',
        'portfolio_project_hero_luxury' => 'portfolio_project_hero_luxury',
        'project_spec_sheet_luxury' => 'project_spec_sheet_luxury',
        'before_after_slider_luxury' => 'before_after_slider_luxury',
        'portfolio_gallery_masonry_luxury' => 'portfolio_gallery_masonry_luxury',
        'marquee_brand' => 'marquee_brand',
        'editorial_split_text_luxury' => 'editorial_split_text_luxury',
        'credentials_grid_luxury' => 'credentials_grid_luxury',
        'timeline_history_luxury' => 'timeline_history_luxury',
        'architectural_process' => 'architectural_process',
        'service_category_cards_luxury' => 'service_category_cards_luxury',
        'service_list_masonry_luxury' => 'service_list_masonry_luxury',
        'locations_grid_luxury' => 'locations_grid_luxury',
        'local_seo_hero_luxury' => 'local_seo_hero_luxury',
        'local_projects_carousel_luxury' => 'local_projects_carousel_luxury',
        'local_faq_accordion_luxury' => 'local_faq_accordion_luxury',
        'newsletter_cta_luxury' => 'newsletter_cta_luxury',
        'enclaves_tabs' => 'enclaves_tabs',
        'consultation_form_split' => 'consultation_form_split',
        'consultation_wizard_luxury' => 'consultation_wizard_luxury',
        'stats_bar' => 'stats_bar',
        'services_grid' => 'services_grid',
        'local_about' => 'local_about',
        'process_steps' => 'process_steps',
        'portfolio_gallery' => 'portfolio_gallery',
        'testimonials' => 'testimonials',
        'faq_section' => 'faq_section',
        'faq_directory' => 'faq_directory',
        'trust_badges' => 'trust_badges',
        'cta_section' => 'cta_section',
        'city_grid' => 'city_grid',
        'blog_strip' => 'blog_strip',
        'service_hero' => 'hero',
        'scp_hero' => 'hero',
        'service_body' => 'rich_text',
        'local_intro' => 'rich_text',
        'benefits_grid' => 'stats_bar',
        'portfolio_preview' => 'portfolio_gallery',
        'city_availability' => 'city_availability',
        'marquee_strip' => 'marquee_strip',
        'parallax_media_band' => 'parallax_media_band',
        'authority_grid' => 'authority_grid',
        'service_area_enclave' => 'service_area_enclave',
        'split_consultation_panel' => 'split_consultation_panel',
    ],

    'strict_unified_page_types' => [
        'home',
        'services_hub',
        'service_category',
        'service',
        'locations_hub',
        'city',
        'theme_layout',
        'template_card',
    ],

    /*
    |--------------------------------------------------------------------------
    | Universal Style Fields (applies to every block)
    |--------------------------------------------------------------------------
    */
    'style_fields' => [
        // Background
        [
            'key' => 'surface_preset',
            'label' => 'Surface Preset',
            'type' => 'select',
            'tab' => 'background',
            'options' => [
                'none' => 'Default',
                'white' => 'White',
                'airy-gradient' => 'Airy Gradient',
                'deep-green' => 'Deep Green',
                'muted-light-green' => 'Muted Light Green',
                'image-dark-overlay' => 'Image-Backed Dark Overlay',
                'dark-strip' => 'Dark Strip',
                'premium-neutral' => 'Premium Neutral',
            ],
        ],
        [
            'key' => 'bg_color',
            'label' => 'Background Color',
            'type' => 'select',
            'tab' => 'background',
            'options' => [
                'none' => 'Transparent',
                'white' => 'White',
                'cream' => 'Cream',
                'gray' => 'Light Gray',
                'forest' => 'Forest Green',
                'dark' => 'Dark',
            ],
        ],
        ['key' => 'bg_image_id', 'label' => 'Background Image', 'type' => 'media', 'tab' => 'background'],
        [
            'key' => 'overlay_preset',
            'label' => 'Overlay Preset',
            'type' => 'select',
            'tab' => 'background',
            'options' => [
                'none' => 'None',
                'light' => 'Light (Glass)',
                'dark' => 'Dark',
                'forest' => 'Forest Tint',
            ],
        ],
        [
            'key' => 'bg_overlay_opacity',
            'label' => 'Overlay Opacity',
            'type' => 'range',
            'tab' => 'background',
            'min' => 0,
            'max' => 100,
            'step' => 5,
            'default' => 50,
        ],
        [
            'key' => 'surface_style',
            'label' => 'Legacy Surface Style',
            'type' => 'select',
            'tab' => 'background',
            'options' => [
                'none' => 'Default',
                'sage-gradient' => 'Sage Gradient',
                'forest-gradient' => 'Forest Gradient',
                'cream-panel' => 'Cream Panel',
                'glass-light' => 'Glass Light',
                'glass-dark' => 'Glass Dark',
                'stone-wash' => 'Stone Wash',
            ],
        ],
        [
            'key' => 'glass_effect',
            'label' => 'Glass Effect',
            'type' => 'select',
            'tab' => 'background',
            'options' => ['none' => 'None', 'subtle' => 'Subtle', 'strong' => 'Strong'],
        ],

        // Transitions
        [
            'key' => 'transition_top',
            'label' => 'Transition Top',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => [
                'none' => 'None',
                'fade-to-white' => 'Fade to White',
                'fade-from-white' => 'Fade from White',
                'fade-to-airy' => 'Fade to Airy',
                'fade-from-airy' => 'Fade from Airy',
                'dark-blend' => 'Dark Blend',
            ],
        ],
        [
            'key' => 'transition_bottom',
            'label' => 'Transition Bottom',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => [
                'none' => 'None',
                'fade-to-white' => 'Fade to White',
                'fade-from-white' => 'Fade from White',
                'fade-to-airy' => 'Fade to Airy',
                'fade-from-airy' => 'Fade from Airy',
                'dark-blend' => 'Dark Blend',
            ],
        ],

        // Spacing
        [
            'key' => 'spacing_preset',
            'label' => 'Spacing Preset',
            'type' => 'select',
            'tab' => 'spacing',
            'options' => ['none' => 'None', 'compact' => 'Compact', 'section' => 'Section', 'feature' => 'Feature', 'hero' => 'Hero'],
        ],
        [
            'key' => 'padding_top',
            'label' => 'Padding Top',
            'type' => 'select',
            'tab' => 'spacing',
            'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large'],
        ],
        [
            'key' => 'padding_bottom',
            'label' => 'Padding Bottom',
            'type' => 'select',
            'tab' => 'spacing',
            'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large'],
        ],
        [
            'key' => 'padding_left',
            'label' => 'Padding Left',
            'type' => 'select',
            'tab' => 'spacing',
            'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
        ],
        [
            'key' => 'padding_right',
            'label' => 'Padding Right',
            'type' => 'select',
            'tab' => 'spacing',
            'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
        ],
        [
            'key' => 'margin_top',
            'label' => 'Margin Top',
            'type' => 'select',
            'tab' => 'spacing',
            'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large'],
        ],
        [
            'key' => 'margin_bottom',
            'label' => 'Margin Bottom',
            'type' => 'select',
            'tab' => 'spacing',
            'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large'],
        ],

        // Layout
        [
            'key' => 'content_width',
            'label' => 'Content Width Preset',
            'type' => 'select',
            'tab' => 'layout',
            'options' => [
                'default' => 'Default (Container)',
                'full' => 'Full Width',
                '7xl' => 'Wide (1280px)',
                '5xl' => 'Standard (1024px)',
                '3xl' => 'Narrow (768px)',
                'premium-narrow' => 'Editorial Narrow',
            ],
        ],
        [
            'key' => 'max_width',
            'label' => 'Legacy Max Width',
            'type' => 'select',
            'tab' => 'layout',
            'options' => ['full' => 'Full Width', 'xl' => '7xl (1280px)', 'lg' => '5xl (1024px)', 'md' => '3xl (768px)', 'sm' => 'xl (576px)'],
        ],
        [
            'key' => 'section_density_preset',
            'label' => 'Section Density',
            'type' => 'select',
            'tab' => 'layout',
            'options' => [
                'default' => 'Default',
                'compact' => 'Compact',
                'airy' => 'Airy (Luxury)',
            ],
        ],
        [
            'key' => 'text_color',
            'label' => 'Text Color',
            'type' => 'select',
            'tab' => 'layout',
            'options' => ['default' => 'Default', 'white' => 'White', 'dark' => 'Dark', 'forest' => 'Forest Green'],
        ],
        [
            'key' => 'text_align_preset',
            'label' => 'Text Alignment Preset',
            'type' => 'select',
            'tab' => 'layout',
            'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
        ],
        [
            'key' => 'text_align',
            'label' => 'Legacy Text Align',
            'type' => 'select',
            'tab' => 'layout',
            'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
        ],

        // Appearance
        [
            'key' => 'card_skin_preset',
            'label' => 'Card Skin Preset',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => [
                'default' => 'Default',
                'white-border' => 'White Bordered',
                'soft-elevated' => 'Soft Elevated',
                'quiet-hover' => 'Quiet Hover',
                'dark-panel' => 'Dark Panel',
            ],
        ],
        [
            'key' => 'border_style_preset',
            'label' => 'Border Style Preset',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => [
                'none' => 'None',
                'subtle' => 'Subtle (Light)',
                'gold' => 'Gold Accent',
                'dark' => 'Dark Edge',
            ],
        ],
        [
            'key' => 'shadow_elevation_preset',
            'label' => 'Shadow Elevation Preset',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => [
                'none' => 'None',
                'sm' => 'Small (Subtle)',
                'md' => 'Medium (Editorial)',
                'lg' => 'Large (Luxury)',
            ],
        ],
        [
            'key' => 'section_shell',
            'label' => 'Section Shell',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => ['none' => 'None', 'inset-panel' => 'Inset Panel', 'luxury-panel' => 'Luxury Panel', 'soft-panel' => 'Soft Panel'],
        ],
        [
            'key' => 'divider_style',
            'label' => 'Divider Style',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => [
                'none' => 'None',
                'top' => 'Top Border',
                'bottom' => 'Bottom Border',
                'both' => 'Top + Bottom',
                'gold-top' => 'Gold Top',
                'gold-bottom' => 'Gold Bottom',
            ],
        ],
        ['key' => 'rounded', 'label' => 'Rounded Corners', 'type' => 'toggle', 'tab' => 'appearance'],
        [
            'key' => 'border',
            'label' => 'Legacy Border',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => ['none' => 'None', 'light' => 'Light', 'medium' => 'Medium'],
        ],
        [
            'key' => 'shadow',
            'label' => 'Legacy Shadow',
            'type' => 'select',
            'tab' => 'appearance',
            'options' => ['none' => 'None', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
        ],
        ['key' => 'custom_class', 'label' => 'Custom CSS Class', 'type' => 'text', 'tab' => 'appearance'],

        // Advanced (Typography & Layering)
        [
            'key' => 'heading_scale_preset',
            'label' => 'Heading Scale Preset',
            'type' => 'select',
            'tab' => 'typography',
            'options' => [
                'default' => 'Default',
                'display' => 'Display (Hero)',
                'h1' => 'H1 (Primary)',
                'h2' => 'H2 (Section)',
                'h3' => 'H3 (Subsection)',
            ],
        ],
        [
            'key' => 'font_size',
            'label' => 'Global Font Size',
            'type' => 'select',
            'tab' => 'typography',
            'options' => ['default' => 'Default', 'sm' => 'Small', 'lg' => 'Large', 'xl' => 'Extra Large'],
        ],
        [
            'key' => 'font_weight',
            'label' => 'Global Font Weight',
            'type' => 'select',
            'tab' => 'typography',
            'options' => ['normal' => 'Normal', 'medium' => 'Medium', 'semibold' => 'Semibold', 'bold' => 'Bold'],
        ],
        ['key' => 'z_index', 'label' => 'Z-Index', 'type' => 'number', 'tab' => 'layout', 'default' => 0],
        [
            'key' => 'overflow',
            'label' => 'Overflow',
            'type' => 'select',
            'tab' => 'layout',
            'options' => ['visible' => 'Visible', 'hidden' => 'Hidden', 'clip' => 'Clip'],
        ],
    ],

    'style_defaults' => [
        'desktop' => [
            'surface_preset' => 'none',
            'bg_color' => 'none',
            'bg_image_id' => null,
            'overlay_preset' => 'none',
            'bg_overlay_opacity' => 50,
            'surface_style' => 'none',
            'glass_effect' => 'none',
            'transition_top' => 'none',
            'transition_bottom' => 'none',
            'spacing_preset' => 'section',
            'padding_top' => 'lg',
            'padding_bottom' => 'lg',
            'padding_left' => 'md',
            'padding_right' => 'md',
            'margin_top' => 'none',
            'margin_bottom' => 'lg',
            'content_width' => 'default',
            'max_width' => 'full',
            'section_density_preset' => 'default',
            'text_color' => 'default',
            'text_align_preset' => 'left',
            'text_align' => 'left',
            'card_skin_preset' => 'default',
            'border_style_preset' => 'none',
            'shadow_elevation_preset' => 'none',
            'section_shell' => 'none',
            'divider_style' => 'none',
            'rounded' => false,
            'border' => 'none',
            'shadow' => 'none',
            'custom_class' => '',
            'heading_scale_preset' => 'default',
        ],
        'tablet' => [],
        'mobile' => [],
    ],

    'theme_style_defaults' => [
        'desktop' => [
            'surface_preset' => 'none',
            'bg_color' => 'none',
            'bg_image_id' => null,
            'overlay_preset' => 'none',
            'bg_overlay_opacity' => 50,
            'surface_style' => 'none',
            'glass_effect' => 'none',
            'transition_top' => 'none',
            'transition_bottom' => 'none',
            'spacing_preset' => 'none',
            'padding_top' => 'none',
            'padding_bottom' => 'none',
            'padding_left' => 'none',
            'padding_right' => 'none',
            'margin_top' => 'none',
            'margin_bottom' => 'none',
            'content_width' => 'default',
            'max_width' => 'full',
            'section_density_preset' => 'default',
            'text_color' => 'default',
            'text_align_preset' => 'left',
            'text_align' => 'left',
            'card_skin_preset' => 'default',
            'border_style_preset' => 'none',
            'shadow_elevation_preset' => 'none',
            'section_shell' => 'none',
            'divider_style' => 'none',
            'rounded' => false,
            'border' => 'none',
            'shadow' => 'none',
            'custom_class' => '',
            'heading_scale_preset' => 'default',
        ],
        'tablet' => [],
        'mobile' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Block Type Registry
    |--------------------------------------------------------------------------
    */
    'types' => [

        // =====================================================================
        // DATA BLOCKS — Dynamic content from database
        // =====================================================================

        'hero_luxury' => [
            'label' => 'Luxury Hero (FSE)',
            'icon' => 'layout',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'heading_highlight', 'label' => 'Heading Highlight (Italic)', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                ['key' => 'cta_primary_text', 'label' => 'Primary CTA Text', 'type' => 'text'],
                ['key' => 'cta_primary_url', 'label' => 'Primary CTA URL', 'type' => 'text'],
                ['key' => 'cta_secondary_text', 'label' => 'Secondary CTA Text', 'type' => 'text'],
                ['key' => 'cta_secondary_url', 'label' => 'Secondary CTA URL', 'type' => 'text'],
                ['key' => 'bg_pattern', 'label' => 'Background Pattern URL (Optional)', 'type' => 'text'],
                // Trust badges (optional override, defaults to empty to use standard)
                ['key' => 'badge_1_title', 'label' => 'Badge 1 Title', 'type' => 'text'],
                ['key' => 'badge_1_value', 'label' => 'Badge 1 Value', 'type' => 'text'],
                ['key' => 'badge_2_title', 'label' => 'Badge 2 Title', 'type' => 'text'],
                ['key' => 'badge_2_value', 'label' => 'Badge 2 Value', 'type' => 'text'],
                ['key' => 'badge_3_title', 'label' => 'Badge 3 Title', 'type' => 'text'],
                ['key' => 'badge_3_value', 'label' => 'Badge 3 Value', 'type' => 'text'],
                ['key' => 'badge_4_title', 'label' => 'Badge 4 Title', 'type' => 'text'],
                ['key' => 'badge_4_value', 'label' => 'Badge 4 Value', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
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

        'architectural_standard' => [
            'label' => 'Architectural Standard (Text Centered)',
            'icon' => 'type',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'heading_highlight', 'label' => 'Heading Highlight (Italic)', 'type' => 'text'],
                ['key' => 'paragraph', 'label' => 'Paragraph', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'The Architectural Standard',
                'heading' => 'Built for Properties Where',
                'heading_highlight' => 'Detail Matters',
                'paragraph' => 'Lush Landscape creates private residential outdoor environments where structure, craftsmanship, and visual restraint matter equally. From driveways, patios, and retaining walls to grading and planting, every project is approached with clarity, proportion, and long-term performance in mind.',
            ],
        ],

        'architectural_services' => [
            'label' => 'Architectural Services (4 Cards + Slider)',
            'icon' => 'grid',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                
                ['key' => 'card_1_icon', 'label' => 'Card 1 Icon (FontAwesome class)', 'type' => 'text'],
                ['key' => 'card_1_title', 'label' => 'Card 1 Title', 'type' => 'text'],
                ['key' => 'card_1_desc', 'label' => 'Card 1 Description', 'type' => 'textarea'],
                ['key' => 'card_1_list', 'label' => 'Card 1 List Items (comma separated)', 'type' => 'text'],
                
                ['key' => 'card_2_icon', 'label' => 'Card 2 Icon', 'type' => 'text'],
                ['key' => 'card_2_title', 'label' => 'Card 2 Title', 'type' => 'text'],
                ['key' => 'card_2_desc', 'label' => 'Card 2 Description', 'type' => 'textarea'],
                ['key' => 'card_2_list', 'label' => 'Card 2 List Items', 'type' => 'text'],
                
                ['key' => 'card_3_icon', 'label' => 'Card 3 Icon', 'type' => 'text'],
                ['key' => 'card_3_title', 'label' => 'Card 3 Title', 'type' => 'text'],
                ['key' => 'card_3_desc', 'label' => 'Card 3 Description', 'type' => 'textarea'],
                ['key' => 'card_3_list', 'label' => 'Card 3 List Items', 'type' => 'text'],
                
                ['key' => 'card_4_icon', 'label' => 'Card 4 Icon', 'type' => 'text'],
                ['key' => 'card_4_title', 'label' => 'Card 4 Title', 'type' => 'text'],
                ['key' => 'card_4_desc', 'label' => 'Card 4 Description', 'type' => 'textarea'],
                ['key' => 'card_4_list', 'label' => 'Card 4 List Items', 'type' => 'text'],

                ['key' => 'slider_img_1', 'label' => 'Slider Image 1 URL', 'type' => 'text'],
                ['key' => 'slider_img_2', 'label' => 'Slider Image 2 URL', 'type' => 'text'],
                ['key' => 'slider_eyebrow', 'label' => 'Slider Eyebrow', 'type' => 'text'],
                ['key' => 'slider_heading', 'label' => 'Slider Heading', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
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

        'parallax_banner_luxury' => [
            'label' => 'Parallax Banner (Luxury)',
            'icon' => 'image',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'heading_highlight', 'label' => 'Heading Highlight (Italic)', 'type' => 'text'],
                ['key' => 'bg_image', 'label' => 'Background Image URL', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'The Intersection',
                'heading' => 'Designing the Space Between',
                'heading_highlight' => 'Nature & Architecture.',
                'bg_image' => 'https://images.unsplash.com/photo-1600607686527-6fb886090705?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp',
            ],
        ],

        'portfolio_slider' => [
            'label' => 'Portfolio Slider (Horizontal GSAP)',
            'icon' => 'sliders',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'link_text', 'label' => 'Link Text', 'type' => 'text'],
                ['key' => 'link_url', 'label' => 'Link URL', 'type' => 'text'],
                
                ['key' => 'item_1_img', 'label' => 'Item 1 Image URL', 'type' => 'text'],
                ['key' => 'item_1_eyebrow', 'label' => 'Item 1 Eyebrow', 'type' => 'text'],
                ['key' => 'item_1_title', 'label' => 'Item 1 Title', 'type' => 'text'],
                
                ['key' => 'item_2_img', 'label' => 'Item 2 Image URL', 'type' => 'text'],
                ['key' => 'item_2_eyebrow', 'label' => 'Item 2 Eyebrow', 'type' => 'text'],
                ['key' => 'item_2_title', 'label' => 'Item 2 Title', 'type' => 'text'],
                
                ['key' => 'item_3_img', 'label' => 'Item 3 Image URL', 'type' => 'text'],
                ['key' => 'item_3_eyebrow', 'label' => 'Item 3 Eyebrow', 'type' => 'text'],
                ['key' => 'item_3_title', 'label' => 'Item 3 Title', 'type' => 'text'],
                
                ['key' => 'item_4_img', 'label' => 'Item 4 Image URL', 'type' => 'text'],
                ['key' => 'item_4_eyebrow', 'label' => 'Item 4 Eyebrow', 'type' => 'text'],
                ['key' => 'item_4_title', 'label' => 'Item 4 Title', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
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

        'portfolio_masonry_grid_luxury' => [
            'label' => 'Portfolio Masonry (Luxury)',
            'icon' => 'layout-grid',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Our Legacy',
                'heading' => 'Completed<br>Environments',
                'description' => 'A curated selection of luxury landscape constructions across the Greater Toronto Area.',
            ],
        ],

        'portfolio_project_hero_luxury' => [
            'label' => 'Project Hero (Luxury)',
            'icon' => 'image',
            'category' => 'data',
            'content_fields' => [],
            'data_source' => null,
            'defaults' => [],
        ],

        'project_spec_sheet_luxury' => [
            'label' => 'Project Spec Sheet (Luxury)',
            'icon' => 'file-text',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Project Overview',
            ],
        ],

        'before_after_slider_luxury' => [
            'label' => 'Before/After Slider (Luxury)',
            'icon' => 'sliders-horizontal',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'heading' => 'Site Transformation',
                'description' => 'Drag the slider to reveal the structural correction and design integration.',
            ],
        ],

        'portfolio_gallery_masonry_luxury' => [
            'label' => 'Project Gallery Masonry (Luxury)',
            'icon' => 'layout-grid',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'heading' => 'Visual Documentation',
            ],
        ],

        'marquee_brand' => [
            'label' => 'Brand Marquee (Dark)',
            'icon' => 'type',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'text', 'label' => 'Marquee Text (Use &bull; for dots)', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'text' => 'ARCHITECTURAL PRECISION &nbsp;&nbsp;&bull;&nbsp;&nbsp; UNYIELDING QUALITY &nbsp;&nbsp;&bull;&nbsp;&nbsp; PREMIUM STONE SELECTION &nbsp;&nbsp;&bull;&nbsp;&nbsp; EXPERT CRAFTSMANSHIP &nbsp;&nbsp;&bull;&nbsp;&nbsp;',
            ],
        ],

        'editorial_split_text_luxury' => [
            'label' => 'Editorial Split Text (Luxury)',
            'icon' => 'columns',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'heading_highlight', 'label' => 'Heading Highlight (Italic)', 'type' => 'text'],
                ['key' => 'paragraph_1', 'label' => 'Paragraph 1', 'type' => 'textarea'],
                ['key' => 'paragraph_2', 'label' => 'Paragraph 2', 'type' => 'textarea'],
                ['key' => 'signature_name', 'label' => 'Signature Name (Optional)', 'type' => 'text'],
                ['key' => 'signature_title', 'label' => 'Signature Title (Optional)', 'type' => 'text'],
                ['key' => 'image', 'label' => 'Image URL', 'type' => 'text'],
                ['key' => 'image_position', 'label' => 'Image Position (left or right)', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Our Philosophy',
                'heading' => 'Built for Properties Where',
                'heading_highlight' => 'Detail Matters',
                'paragraph_1' => 'Lush Landscape creates private residential outdoor environments where structure, craftsmanship, and visual restraint matter equally.',
                'paragraph_2' => 'We reject the transactional nature of the landscaping industry. Instead, we operate as a design-build firm committed to long-term architectural integrity, executing complex master plans with absolute precision.',
                'signature_name' => '',
                'signature_title' => '',
                'image' => 'https://images.unsplash.com/photo-1600607686527-6fb886090705?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp',
                'image_position' => 'left',
            ],
        ],

        'credentials_grid_luxury' => [
            'label' => 'Credentials Grid (Luxury)',
            'icon' => 'shield-check',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                
                ['key' => 'cred_1_icon', 'label' => 'Credential 1 Icon (Lucide)', 'type' => 'text'],
                ['key' => 'cred_1_title', 'label' => 'Credential 1 Title', 'type' => 'text'],
                ['key' => 'cred_1_desc', 'label' => 'Credential 1 Description', 'type' => 'textarea'],
                
                ['key' => 'cred_2_icon', 'label' => 'Credential 2 Icon', 'type' => 'text'],
                ['key' => 'cred_2_title', 'label' => 'Credential 2 Title', 'type' => 'text'],
                ['key' => 'cred_2_desc', 'label' => 'Credential 2 Description', 'type' => 'textarea'],
                
                ['key' => 'cred_3_icon', 'label' => 'Credential 3 Icon', 'type' => 'text'],
                ['key' => 'cred_3_title', 'label' => 'Credential 3 Title', 'type' => 'text'],
                ['key' => 'cred_3_desc', 'label' => 'Credential 3 Description', 'type' => 'textarea'],
                
                ['key' => 'cred_4_icon', 'label' => 'Credential 4 Icon', 'type' => 'text'],
                ['key' => 'cred_4_title', 'label' => 'Credential 4 Title', 'type' => 'text'],
                ['key' => 'cred_4_desc', 'label' => 'Credential 4 Description', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'The Firm',
                'heading' => 'Institutional Grade Security',
                
                'cred_1_icon' => 'shield-check',
                'cred_1_title' => '10-Year Workmanship Warranty',
                'cred_1_desc' => 'Every structural installation is backed by a rigorous, written decade-long warranty, ensuring complete peace of mind.',
                
                'cred_2_icon' => 'file-check-2',
                'cred_2_title' => '$5M Liability Insurance',
                'cred_2_desc' => 'We carry comprehensive coverage specifically designed for complex residential and estate-level construction.',
                
                'cred_3_icon' => 'award',
                'cred_3_title' => 'WSIB Cleared & Compliant',
                'cred_3_desc' => 'Full Workers\' Safety and Insurance Board clearance guarantees our in-house teams are protected and professional.',
                
                'cred_4_icon' => 'hard-hat',
                'cred_4_title' => 'In-House Execution',
                'cred_4_desc' => 'We do not broker out our core trades. Our master stonemasons and structural teams are dedicated Lush Landscape personnel.',
            ],
        ],

        'timeline_history_luxury' => [
            'label' => 'Timeline History (Luxury)',
            'icon' => 'clock',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                
                ['key' => 'year_1', 'label' => 'Year 1', 'type' => 'text'],
                ['key' => 'title_1', 'label' => 'Title 1', 'type' => 'text'],
                ['key' => 'desc_1', 'label' => 'Description 1', 'type' => 'textarea'],
                
                ['key' => 'year_2', 'label' => 'Year 2', 'type' => 'text'],
                ['key' => 'title_2', 'label' => 'Title 2', 'type' => 'text'],
                ['key' => 'desc_2', 'label' => 'Description 2', 'type' => 'textarea'],
                
                ['key' => 'year_3', 'label' => 'Year 3', 'type' => 'text'],
                ['key' => 'title_3', 'label' => 'Title 3', 'type' => 'text'],
                ['key' => 'desc_3', 'label' => 'Description 3', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Our Evolution',
                'heading' => 'A Heritage of Craftsmanship',
                
                'year_1' => '2018',
                'title_1' => 'The Foundation',
                'desc_1' => 'Lush Landscape Service was established with a singular focus on elevating residential stonework and structural hardscaping in the Greater Toronto Area.',
                
                'year_2' => '2021',
                'title_2' => 'Scale & Maturation',
                'desc_2' => 'Expanded our core operations to handle full estate transformations, integrating luxury outdoor living spaces and high-end pool construction into our repertoire.',
                
                'year_3' => '2024',
                'title_3' => 'The Architectural Standard',
                'desc_3' => 'Solidified our position as the design-build firm of choice for the top 1% of the GTA, partnering exclusively with prestige properties and heritage estates.',
            ],
        ],

        'architectural_process' => [
            'label' => 'Architectural Process (5 Steps Sticky)',
            'icon' => 'list',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                
                ['key' => 'step_1_phase', 'label' => 'Step 1 Phase Label', 'type' => 'text'],
                ['key' => 'step_1_title', 'label' => 'Step 1 Title', 'type' => 'text'],
                ['key' => 'step_1_desc', 'label' => 'Step 1 Description', 'type' => 'textarea'],
                
                ['key' => 'step_2_phase', 'label' => 'Step 2 Phase Label', 'type' => 'text'],
                ['key' => 'step_2_title', 'label' => 'Step 2 Title', 'type' => 'text'],
                ['key' => 'step_2_desc', 'label' => 'Step 2 Description', 'type' => 'textarea'],
                
                ['key' => 'step_3_phase', 'label' => 'Step 3 Phase Label', 'type' => 'text'],
                ['key' => 'step_3_title', 'label' => 'Step 3 Title', 'type' => 'text'],
                ['key' => 'step_3_desc', 'label' => 'Step 3 Description', 'type' => 'textarea'],
                
                ['key' => 'step_4_phase', 'label' => 'Step 4 Phase Label', 'type' => 'text'],
                ['key' => 'step_4_title', 'label' => 'Step 4 Title', 'type' => 'text'],
                ['key' => 'step_4_desc', 'label' => 'Step 4 Description', 'type' => 'textarea'],
                
                ['key' => 'step_5_phase', 'label' => 'Step 5 Phase Label', 'type' => 'text'],
                ['key' => 'step_5_title', 'label' => 'Step 5 Title', 'type' => 'text'],
                ['key' => 'step_5_desc', 'label' => 'Step 5 Description', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
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

        'service_category_cards_luxury' => [
            'label' => 'Service Category Cards (Luxury)',
            'icon' => 'layers',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Our Capabilities',
                'heading' => 'Architectural<br>Disciplines',
                'description' => 'Explore our core construction categories, engineered for longevity and absolute visual restraint.',
            ],
        ],

        'service_list_masonry_luxury' => [
            'label' => 'Service List Masonry (Luxury)',
            'icon' => 'layout-grid',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Specialized Services',
                'heading' => 'Targeted<br>Execution',
                'description' => 'Explore the specific installations and capabilities within this architectural discipline.',
            ],
        ],

        'locations_grid_luxury' => [
            'label' => 'Locations Grid (Luxury)',
            'icon' => 'map',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Service Footprint',
                'heading' => 'Geographical<br>Execution',
                'description' => 'Providing landscape execution exclusively for the Greater Toronto Area\'s most established residential communities.',
            ],
        ],

        'local_seo_hero_luxury' => [
            'label' => 'Local SEO Hero (Luxury)',
            'icon' => 'map-pin',
            'category' => 'data',
            'content_fields' => [],
            'data_source' => null,
            'defaults' => [],
        ],

        'local_projects_carousel_luxury' => [
            'label' => 'Local Projects Carousel (Luxury)',
            'icon' => 'image',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Local Portfolio',
                'heading' => 'Recent Installations in [City]',
            ],
        ],

        'local_faq_accordion_luxury' => [
            'label' => 'Local FAQ Accordion (Luxury)',
            'icon' => 'help-circle',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Project Guidelines',
                'heading' => 'Common Inquiries',
            ],
        ],

        'newsletter_cta_luxury' => [
            'label' => 'Newsletter CTA (Luxury)',
            'icon' => 'mail',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'subtext', 'label' => 'Subtext', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Stay Updated',
                'heading' => 'Landscape Insights & Project Planning',
                'subtext' => 'Join 2,000+ Ontario homeowners getting our free monthly newsletter.',
            ],
        ],

        'enclaves_tabs' => [
            'label' => 'Executive Enclaves (Tabs)',
            'icon' => 'map-pin',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                
                ['key' => 'tab_1_name', 'label' => 'Tab 1 Name (City)', 'type' => 'text'],
                ['key' => 'tab_1_items', 'label' => 'Tab 1 Items (comma separated)', 'type' => 'textarea'],
                
                ['key' => 'tab_2_name', 'label' => 'Tab 2 Name', 'type' => 'text'],
                ['key' => 'tab_2_items', 'label' => 'Tab 2 Items', 'type' => 'textarea'],
                
                ['key' => 'tab_3_name', 'label' => 'Tab 3 Name', 'type' => 'text'],
                ['key' => 'tab_3_items', 'label' => 'Tab 3 Items', 'type' => 'textarea'],
                
                ['key' => 'tab_4_name', 'label' => 'Tab 4 Name', 'type' => 'text'],
                ['key' => 'tab_4_items', 'label' => 'Tab 4 Items', 'type' => 'textarea'],
                
                ['key' => 'tab_5_name', 'label' => 'Tab 5 Name', 'type' => 'text'],
                ['key' => 'tab_5_items', 'label' => 'Tab 5 Items', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
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

        'consultation_form_split' => [
            'label' => 'Consultation Form (Split Panel Luxury)',
            'icon' => 'edit-3',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ],
            'data_source' => 'form_slug',
            'defaults' => [
                'eyebrow' => 'Initiate Project',
                'heading' => 'Request a<br>Consultation',
                'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours.',
            ],
        ],

        'consultation_wizard_luxury' => [
            'label' => 'Consultation Wizard (Multi-step Luxury)',
            'icon' => 'list-checks',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                
                ['key' => 'badge_1_icon', 'label' => 'Badge 1 Icon (Lucide)', 'type' => 'text'],
                ['key' => 'badge_1_text', 'label' => 'Badge 1 Text', 'type' => 'text'],
                ['key' => 'badge_2_icon', 'label' => 'Badge 2 Icon', 'type' => 'text'],
                ['key' => 'badge_2_text', 'label' => 'Badge 2 Text', 'type' => 'text'],
                ['key' => 'badge_3_icon', 'label' => 'Badge 3 Icon', 'type' => 'text'],
                ['key' => 'badge_3_text', 'label' => 'Badge 3 Text', 'type' => 'text'],
                ['key' => 'badge_4_icon', 'label' => 'Badge 4 Icon', 'type' => 'text'],
                ['key' => 'badge_4_text', 'label' => 'Badge 4 Text', 'type' => 'text'],
            ],
            'data_source' => 'form_slug',
            'defaults' => [
                'eyebrow' => 'Project Intake',
                'heading' => 'Request a<br>Design Consultation',
                'description' => 'Share your architectural vision. Our project concierge will review your requirements and respond within 24 hours to schedule an on-site assessment.',
                'badge_1_icon' => 'shield-check',
                'badge_1_text' => '10-Year Warranty',
                'badge_2_icon' => 'file-check-2',
                'badge_2_text' => '$5M Liability Insured',
                'badge_3_icon' => 'award',
                'badge_3_text' => 'WSIB Compliant',
                'badge_4_icon' => 'hard-hat',
                'badge_4_text' => 'In-House Execution',
            ],
        ],

        'hero' => [
            'label' => 'Hero Banner',
            'icon' => 'layout',
            'category' => 'data',
            'governance' => [
                'required_fields' => ['heading'],
                'variants' => [
                    'editorial' => [
                        'label' => 'Editorial',
                        'visible_fields' => ['heading', 'subtitle', 'eyebrow', 'cta_primary_text', 'cta_primary_url', 'cta_secondary_text', 'cta_secondary_url', 'hero_media_id', 'video_url', 'extra_image_ids', 'overlay_preset', 'overlay_opacity', 'align', 'height'],
                    ],
                ],
            ],
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow/Tag', 'type' => 'text'],
                ['key' => 'cta_primary_text', 'label' => 'Primary CTA Text', 'type' => 'text'],
                ['key' => 'cta_primary_url', 'label' => 'Primary CTA URL', 'type' => 'text'],
                ['key' => 'cta_secondary_text', 'label' => 'Secondary CTA Text', 'type' => 'text'],
                ['key' => 'cta_secondary_url', 'label' => 'Secondary CTA URL', 'type' => 'text'],
                ['key' => 'hero_media_id', 'label' => 'Hero Image', 'type' => 'media'],
                ['key' => 'video_url', 'label' => 'Background Video URL', 'type' => 'text'],
                ['key' => 'extra_image_ids', 'label' => 'Additional Slider Images', 'type' => 'media_multi'],
                [
                    'key' => 'overlay_preset',
                    'label' => 'Overlay Preset',
                    'type' => 'select',
                    'options' => [
                        'gradient' => 'Gradient',
                        'solid' => 'Solid',
                        'none' => 'None',
                    ],
                ],
                ['key' => 'overlay_opacity', 'label' => 'Overlay Opacity', 'type' => 'text'],
                [
                    'key' => 'align',
                    'label' => 'Text Alignment',
                    'type' => 'select',
                    'options' => [
                        'center' => 'Center',
                        'left' => 'Left',
                    ],
                ],
                [
                    'key' => 'height',
                    'label' => 'Hero Height',
                    'type' => 'select',
                    'options' => [
                        'viewport' => 'Viewport',
                        'tall' => 'Tall',
                        'standard' => 'Standard',
                    ],
                ],
            ],
            'data_source' => null, // hero is content-driven
            'defaults' => [
                'heading' => '',
                'subtitle' => '',
                'eyebrow' => '',
                'cta_primary_text' => 'Book a Consultation',
                'cta_primary_url' => '/contact',
                'cta_secondary_text' => '',
                'cta_secondary_url' => '/portfolio',
                'hero_media_id' => null,
                'video_url' => '',
                'extra_image_ids' => [],
                'overlay_preset' => 'gradient',
                'overlay_opacity' => '50',
                'align' => 'center',
                'height' => 'viewport',
                'variant' => 'editorial',
            ],
        ],
        /*
        |--------------------------------------------------------------------------
        | Theme / Site Builder Blocks (FSE)
        |--------------------------------------------------------------------------
        */
        'site_logo' => [
            'label' => 'Site Logo',
            'icon' => 'image',
            'category' => 'theme',
            'content_fields' => [
                [
                    'key' => 'source',
                    'label' => 'Logo Source',
                    'type' => 'select',
                    'options' => [
                        'auto' => 'Auto',
                        'header_desktop' => 'Header Desktop Logo',
                        'header_mobile' => 'Header Mobile Logo',
                        'footer' => 'Footer Logo',
                    ],
                ],
                [
                    'key' => 'size',
                    'label' => 'Logo Size',
                    'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['auto' => 'Auto', 'light' => 'Light', 'dark' => 'Dark', 'muted' => 'Muted'],
                ],
                ['key' => 'show_tagline', 'label' => 'Show Tagline', 'type' => 'toggle'],
                ['key' => 'tagline', 'label' => 'Custom Tagline Override', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'source' => 'auto',
                'size' => 'lg',
                'tone' => 'auto',
                'show_tagline' => false,
                'tagline' => '',
            ],
        ],
        'theme_header_shell' => [
            'label' => 'Theme Header Shell',
            'icon' => 'panel-top',
            'category' => 'theme',
            'governance' => [
                'allowed_page_types' => ['theme_layout'],
                'supports_children_rules' => [
                    'slot_key' => '_layout_slot',
                    'allowed_slots' => ['left', 'center', 'right', 'mobile'],
                    'required_slots' => ['left', 'center', 'right'],
                ],
            ],
            'supports_children' => true,
            'content_fields' => [
                [
                    'key' => 'mode',
                    'label' => 'Header Surface',
                    'type' => 'select',
                    'options' => ['glass' => 'Glass', 'solid' => 'Solid', 'transparent' => 'Transparent'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark / White Text', 'light' => 'Light / Ink Text'],
                ],
                ['key' => 'sticky', 'label' => 'Sticky Header', 'type' => 'toggle'],
                ['key' => 'compact_on_scroll', 'label' => 'Compact on Scroll', 'type' => 'toggle'],
                ['key' => 'show_divider', 'label' => 'Show Bottom Divider', 'type' => 'toggle'],
                ['key' => 'show_shadow_on_scroll', 'label' => 'Show Shadow on Scroll', 'type' => 'toggle'],
                [
                    'key' => 'desktop_height',
                    'label' => 'Desktop Height',
                    'type' => 'select',
                    'options' => ['compact' => 'Compact', 'standard' => 'Standard', 'tall' => 'Tall'],
                ],
                [
                    'key' => 'scrolled_height',
                    'label' => 'Scrolled Height',
                    'type' => 'select',
                    'options' => ['tight' => 'Tight', 'compact' => 'Compact', 'standard' => 'Standard'],
                ],
                [
                    'key' => 'content_width',
                    'label' => 'Content Width',
                    'type' => 'select',
                    'options' => ['7xl' => '7xl', 'wide' => 'Wide', 'full' => 'Full Width'],
                ],
                [
                    'key' => 'mobile_overlay_style',
                    'label' => 'Mobile Overlay Style',
                    'type' => 'select',
                    'options' => ['fullscreen' => 'Fullscreen', 'sheet' => 'Sheet'],
                ],
                [
                    'key' => 'mobile_overlay_tone',
                    'label' => 'Mobile Overlay Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark', 'light' => 'Light'],
                ],
                ['key' => 'mobile_menu_label', 'label' => 'Mobile Menu Label', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'mode' => 'glass',
                'tone' => 'dark',
                'sticky' => true,
                'compact_on_scroll' => true,
                'show_divider' => true,
                'show_shadow_on_scroll' => true,
                'desktop_height' => 'standard',
                'scrolled_height' => 'compact',
                'content_width' => '7xl',
                'mobile_overlay_style' => 'fullscreen',
                'mobile_overlay_tone' => 'dark',
                'mobile_menu_label' => 'Menu',
            ],
        ],
        'navigation_menu' => [
            'label' => 'Navigation Menu',
            'icon' => 'menu',
            'category' => 'theme',
            'content_fields' => [
                [
                    'key' => 'layout',
                    'label' => 'Menu Layout',
                    'type' => 'select',
                    'options' => ['horizontal' => 'Horizontal', 'vertical' => 'Vertical', 'footer' => 'Footer Links', 'mobile_overlay' => 'Mobile Overlay'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark / White Text', 'light' => 'Light / Forest Text', 'muted' => 'Muted'],
                ],
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['luxury' => 'Luxury', 'minimal' => 'Minimal'],
                ],
                ['key' => 'show_services', 'label' => 'Show Services', 'type' => 'toggle'],
                ['key' => 'show_locations', 'label' => 'Show Locations', 'type' => 'toggle'],
                ['key' => 'show_portfolio', 'label' => 'Show Portfolio', 'type' => 'toggle'],
                ['key' => 'show_about', 'label' => 'Show About', 'type' => 'toggle'],
                ['key' => 'show_contact', 'label' => 'Show Contact', 'type' => 'toggle'],
                ['key' => 'service_limit', 'label' => 'Services Limit', 'type' => 'number'],
                ['key' => 'city_limit', 'label' => 'Cities Limit', 'type' => 'number'],
            ],
            'data_source' => null,
            'defaults' => [
                'layout' => 'horizontal',
                'tone' => 'dark',
                'style' => 'luxury',
                'show_services' => true,
                'show_locations' => true,
                'show_portfolio' => true,
                'show_about' => true,
                'show_contact' => true,
                'service_limit' => 6,
                'city_limit' => 8,
            ],
        ],
        'post_content' => [
            'label' => 'Post Content',
            'icon' => 'file-text',
            'category' => 'theme',
            'content_fields' => [],
            'data_source' => null,
            'defaults' => [],
        ],
        'theme_meta_data' => [
            'label' => 'Theme Meta Data',
            'icon' => 'hash',
            'category' => 'theme',
            'content_fields' => [
                ['key' => 'meta_key', 'label' => 'Meta Key (e.g. phone, footer_copyright_text)', 'type' => 'text'],
                [
                    'key' => 'display',
                    'label' => 'Display',
                    'type' => 'select',
                    'options' => ['inline' => 'Inline', 'stacked' => 'Stacked', 'pill' => 'Pill', 'paragraph' => 'Paragraph'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['inherit' => 'Inherit', 'light' => 'Light', 'dark' => 'Dark', 'accent' => 'Accent'],
                ],
                [
                    'key' => 'icon',
                    'label' => 'Icon',
                    'type' => 'select',
                    'options' => ['auto' => 'Auto', 'none' => 'None', 'phone' => 'Phone', 'mail' => 'Email', 'map-pin' => 'Map Pin', 'star' => 'Star', 'clock' => 'Clock'],
                ],
                ['key' => 'prefix', 'label' => 'Label Prefix', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'meta_key' => 'phone',
                'display' => 'inline',
                'tone' => 'inherit',
                'icon' => 'auto',
                'prefix' => '',
            ],
        ],
        'theme_contact_strip' => [
            'label' => 'Theme Contact Strip',
            'icon' => 'phone-call',
            'category' => 'theme',
            'content_fields' => [
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['compact' => 'Compact', 'chips' => 'Chips', 'stacked' => 'Stacked'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark', 'light' => 'Light', 'muted' => 'Muted'],
                ],
                ['key' => 'show_phone', 'label' => 'Show Phone', 'type' => 'toggle'],
                ['key' => 'show_email', 'label' => 'Show Email', 'type' => 'toggle'],
                ['key' => 'show_rating', 'label' => 'Show Google Rating', 'type' => 'toggle'],
                ['key' => 'show_hours', 'label' => 'Show Hours', 'type' => 'toggle'],
            ],
            'data_source' => null,
            'defaults' => [
                'variant' => 'compact',
                'tone' => 'dark',
                'show_phone' => true,
                'show_email' => false,
                'show_rating' => true,
                'show_hours' => false,
            ],
        ],
        'theme_cta_group' => [
            'label' => 'Theme CTA Group',
            'icon' => 'mouse-pointer-click',
            'category' => 'theme',
            'content_fields' => [
                [
                    'key' => 'align',
                    'label' => 'Alignment',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark', 'light' => 'Light'],
                ],
                ['key' => 'primary_text', 'label' => 'Primary Button Text', 'type' => 'text'],
                ['key' => 'primary_url', 'label' => 'Primary Button URL', 'type' => 'text'],
                [
                    'key' => 'primary_style',
                    'label' => 'Primary Button Style',
                    'type' => 'select',
                    'options' => ['primary' => 'Primary', 'white' => 'White', 'ghost' => 'Ghost'],
                ],
                ['key' => 'secondary_text', 'label' => 'Secondary Button Text', 'type' => 'text'],
                ['key' => 'secondary_url', 'label' => 'Secondary Button URL', 'type' => 'text'],
                [
                    'key' => 'secondary_style',
                    'label' => 'Secondary Button Style',
                    'type' => 'select',
                    'options' => ['ghost' => 'Ghost', 'primary' => 'Primary', 'white' => 'White'],
                ],
            ],
            'data_source' => null,
            'defaults' => [
                'align' => 'right',
                'tone' => 'dark',
                'primary_text' => 'Book a Consultation',
                'primary_url' => '/contact',
                'primary_style' => 'ghost',
                'secondary_text' => '',
                'secondary_url' => '',
                'secondary_style' => 'white',
            ],
        ],
        'theme_social_links' => [
            'label' => 'Theme Social Links',
            'icon' => 'share-2',
            'category' => 'theme',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                [
                    'key' => 'source',
                    'label' => 'Source',
                    'type' => 'select',
                    'options' => ['settings' => 'Use Site Settings', 'manual' => 'Manual Links'],
                ],
                [
                    'key' => 'align',
                    'label' => 'Alignment',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark', 'light' => 'Light'],
                ],
                [
                    'key' => 'size',
                    'label' => 'Icon Size',
                    'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
                ],
                [
                    'key' => 'links',
                    'label' => 'Manual Links',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'platform', 'label' => 'Platform', 'type' => 'text'],
                        ['key' => 'url', 'label' => 'URL', 'type' => 'text'],
                    ],
                ],
            ],
            'data_source' => null,
            'defaults' => [
                'heading' => '',
                'source' => 'settings',
                'align' => 'left',
                'tone' => 'dark',
                'size' => 'md',
                'links' => [],
            ],
        ],
        'theme_newsletter_panel' => [
            'label' => 'Theme Newsletter Panel',
            'icon' => 'mail-plus',
            'category' => 'theme',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark', 'cream' => 'Cream', 'light' => 'Light'],
                ],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['split' => 'Split', 'stacked' => 'Stacked'],
                ],
                ['key' => 'placeholder', 'label' => 'Input Placeholder', 'type' => 'text'],
                ['key' => 'button_text', 'label' => 'Button Text', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Stay Updated',
                'heading' => '',
                'description' => '',
                'tone' => 'dark',
                'layout' => 'split',
                'placeholder' => 'your@email.com',
                'button_text' => 'Subscribe',
            ],
        ],
        'theme_footer_columns' => [
            'label' => 'Theme Footer Columns',
            'icon' => 'columns-3',
            'category' => 'theme',
            'content_fields' => [
                [
                    'key' => 'source',
                    'label' => 'Columns Source',
                    'type' => 'select',
                    'options' => ['settings' => 'Footer Settings', 'auto' => 'Auto Generated'],
                ],
                ['key' => 'show_services', 'label' => 'Show Services Column', 'type' => 'toggle'],
                ['key' => 'show_locations', 'label' => 'Show Locations Column', 'type' => 'toggle'],
                ['key' => 'show_company', 'label' => 'Show Company Column', 'type' => 'toggle'],
                ['key' => 'services_heading', 'label' => 'Services Heading', 'type' => 'text'],
                ['key' => 'locations_heading', 'label' => 'Locations Heading', 'type' => 'text'],
                ['key' => 'company_heading', 'label' => 'Company Heading', 'type' => 'text'],
                ['key' => 'show_call_panel', 'label' => 'Show Call Panel', 'type' => 'toggle'],
            ],
            'data_source' => null,
            'defaults' => [
                'source' => 'settings',
                'show_services' => true,
                'show_locations' => true,
                'show_company' => true,
                'services_heading' => 'Services',
                'locations_heading' => 'Locations',
                'company_heading' => 'Company',
                'show_call_panel' => true,
            ],
        ],
        'theme_legal_bar' => [
            'label' => 'Theme Legal Bar',
            'icon' => 'scale',
            'category' => 'theme',
            'content_fields' => [
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark', 'light' => 'Light'],
                ],
                ['key' => 'show_copyright', 'label' => 'Show Copyright', 'type' => 'toggle'],
                [
                    'key' => 'links_source',
                    'label' => 'Links Source',
                    'type' => 'select',
                    'options' => ['settings' => 'Footer Settings', 'default' => 'Default Links', 'custom' => 'Custom Links'],
                ],
                ['key' => 'custom_links_text', 'label' => 'Custom Links (Label|URL per line)', 'type' => 'textarea'],
            ],
            'data_source' => null,
            'defaults' => [
                'tone' => 'dark',
                'show_copyright' => true,
                'links_source' => 'settings',
                'custom_links_text' => '',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Premium Section Families (Phase B)
        |--------------------------------------------------------------------------
        */
        'marquee_strip' => [
            'label' => 'Marquee Strip',
            'icon' => 'move-horizontal',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'text_items', 'label' => 'Text Items (Comma separated)', 'type' => 'textarea'],
                [
                    'key' => 'separator_style',
                    'label' => 'Separator Style',
                    'type' => 'select',
                    'options' => ['dot' => 'Dot', 'star' => 'Star', 'line' => 'Line', 'none' => 'None'],
                ],
                [
                    'key' => 'speed',
                    'label' => 'Speed Preset',
                    'type' => 'select',
                    'options' => ['slow' => 'Slow', 'normal' => 'Normal', 'fast' => 'Fast'],
                ],
                [
                    'key' => 'direction',
                    'label' => 'Direction',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'right' => 'Right'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'dark' => 'Dark', 'forest' => 'Forest'],
                ],
            ],
            'defaults' => [
                'text_items' => 'Premium Landscape Design, Expert Installation, 10-Year Warranty, Interlocking & Stonework',
                'separator_style' => 'star',
                'speed' => 'normal',
                'direction' => 'left',
                'tone' => 'dark',
            ],
        ],

        'parallax_media_band' => [
            'label' => 'Parallax Media Band',
            'icon' => 'monitor-play',
            'category' => 'media',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Headline Overlay', 'type' => 'text'],
                ['key' => 'subheadline', 'label' => 'Subheadline Overlay', 'type' => 'textarea'],
                ['key' => 'media_id', 'label' => 'Background Image', 'type' => 'media'],
                ['key' => 'video_url', 'label' => 'Video URL (Optional)', 'type' => 'text'],
                [
                    'key' => 'parallax_intensity',
                    'label' => 'Parallax Intensity',
                    'type' => 'select',
                    'options' => ['none' => 'None', 'subtle' => 'Subtle', 'medium' => 'Medium', 'strong' => 'Strong'],
                ],
                [
                    'key' => 'overlay_preset',
                    'label' => 'Overlay Preset',
                    'type' => 'select',
                    'options' => ['dark' => 'Dark', 'light' => 'Light', 'forest' => 'Forest Gradient', 'none' => 'None'],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'subheadline' => '',
                'media_id' => null,
                'video_url' => '',
                'parallax_intensity' => 'medium',
                'overlay_preset' => 'dark',
            ],
        ],

        'authority_grid' => [
            'label' => 'Authority & Standards Grid',
            'icon' => 'shield-check',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'introduction', 'label' => 'Introduction', 'type' => 'textarea'],
                [
                    'key' => 'card_skin',
                    'label' => 'Card Skin',
                    'type' => 'select',
                    'options' => ['premium-bordered' => 'Premium Bordered', 'elevated' => 'Elevated', 'minimal' => 'Minimal'],
                ],
                [
                    'key' => 'items',
                    'label' => 'Standards/Authority Items',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Short Description', 'type' => 'textarea'],
                    ],
                ],
            ],
            'defaults' => [
                'eyebrow' => 'Our Standards',
                'heading' => 'Built to Last',
                'introduction' => '',
                'card_skin' => 'premium-bordered',
                'items' => [
                    ['icon' => 'shield-check', 'title' => '10-Year Warranty', 'description' => 'Industry-leading protection.'],
                    ['icon' => 'award', 'title' => 'Certified Installers', 'description' => 'ICPI & NCMA certified experts.'],
                    ['icon' => 'leaf', 'title' => 'Premium Materials', 'description' => 'Only the best stone and flora.'],
                ],
            ],
        ],

        'service_area_enclave' => [
            'label' => 'Service Area Enclave',
            'icon' => 'map-pin',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'support_copy', 'label' => 'Support Copy', 'type' => 'textarea'],
                [
                    'key' => 'presentation_mode',
                    'label' => 'Presentation Mode',
                    'type' => 'select',
                    'options' => ['text-led' => 'Premium Text-Led', 'tabbed-enclave' => 'Tabbed Enclave'],
                ],
            ],
            'data_source' => [
                'model' => 'App\\Models\\City',
                'scope' => 'published',
                'filters' => [],
                'limit' => 20,
                'order_by' => 'name',
                'order_dir' => 'asc',
                'manual_ids' => [],
                'with' => [],
            ],
            'defaults' => [
                'eyebrow' => 'Areas We Serve',
                'heading' => 'Proudly Serving the GTA',
                'support_copy' => '',
                'presentation_mode' => 'text-led',
            ],
        ],

        'split_consultation_panel' => [
            'label' => 'Split Consultation Panel',
            'icon' => 'split-square-horizontal',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'editorial_copy', 'label' => 'Editorial Copy', 'type' => 'textarea'],
                ['key' => 'trust_lines', 'label' => 'Trust Lines (Comma separated)', 'type' => 'textarea'],
                ['key' => 'media_id', 'label' => 'Left Panel Image (Optional)', 'type' => 'media'],
                [
                    'key' => 'form_slug',
                    'label' => 'Consultation Form',
                    'type' => 'select_model',
                    'model' => 'App\\Models\\Form',
                    'label_field' => 'name',
                    'value_field' => 'slug',
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'dark' => 'Dark', 'forest' => 'Forest'],
                ],
            ],
            'defaults' => [
                'eyebrow' => 'Get Started',
                'heading' => 'Book a Consultation',
                'editorial_copy' => 'Ready to transform your outdoor space? Reach out to our team to discuss your vision.',
                'trust_lines' => 'Comprehensive property assessment, Expert design advice, Fast response time',
                'media_id' => null,
                'form_slug' => 'consultation',
                'tone' => 'dark',
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Standard Blocks
        |--------------------------------------------------------------------------
        */
        'dynamic_loop' => [
            'label' => 'Dynamic Loop',
            'icon' => 'repeat',
            'category' => 'data',
            'governance' => [
                'required_fields' => ['template_id'],
            ],
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'list' => 'List', 'masonry' => 'Masonry', 'slider' => 'Slider'],
                ],
                [
                    'key' => 'columns',
                    'label' => 'Desktop Columns',
                    'type' => 'select',
                    'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4'],
                ],
                [
                    'key' => 'data_model',
                    'label' => 'Data Source (Model)',
                    'type' => 'select',
                    'options' => [
                        'App\Models\Service' => 'Services',
                        'App\Models\ServiceCategory' => 'Service Categories',
                        'App\Models\City' => 'Cities',
                        'App\Models\PortfolioProject' => 'Portfolio Projects',
                        'App\Models\Review' => 'Reviews',
                        'App\Models\BlogPost' => 'Blog Posts',
                    ],
                ],
                ['key' => 'template_id', 'label' => 'Card Template ID', 'type' => 'number'],
                ['key' => 'limit', 'label' => 'Item Limit', 'type' => 'text'],
            ],
            'data_source' => [
                'model' => 'auto', // Resolved dynamically from data_model content field
                'scope' => 'published',
                'filters' => [
                    'parent_id' => 'auto',
                    'category_id' => 'auto',
                ],
                'limit' => 'auto', // Resolved from content
                'order_by' => 'id',
                'order_dir' => 'desc',
            ],
            'defaults' => [
                'heading' => '',
                'subtitle' => '',
                'layout' => 'grid',
                'columns' => '3',
                'data_model' => 'App\Models\Service',
                'limit' => '12',
            ],
        ],

        'services_grid' => [
            'label' => 'Services Grid',
            'icon' => 'grid-2x2',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'list' => 'List', 'cards' => 'Cards'],
                ],
                [
                    'key' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Card Variant',
                    'type' => 'select',
                    'options' => [
                        'editorial' => 'Editorial',
                        'architectural' => 'Architectural',
                        'minimal' => 'Minimal',
                        'premium-2x2' => 'Premium 2x2 Grid',
                    ],
                ],
                [
                    'key' => 'show_icon',
                    'label' => 'Show Icon',
                    'type' => 'toggle',
                ],
                [
                    'key' => 'show_divider',
                    'label' => 'Show Divider',
                    'type' => 'toggle',
                ],
                [
                    'key' => 'show_usp_list',
                    'label' => 'Show USP List',
                    'type' => 'toggle',
                ],
                [
                    'key' => 'card_cta_label',
                    'label' => 'Card CTA Label',
                    'type' => 'text',
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
                ['key' => 'show_category_nav', 'label' => 'Show Category Navigation', 'type' => 'toggle'],
                ['key' => 'show_view_all', 'label' => 'Show View All Link', 'type' => 'toggle'],
                ['key' => 'view_all_text', 'label' => 'View All Text', 'type' => 'text'],
                ['key' => 'view_all_url', 'label' => 'View All URL', 'type' => 'text'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\Service',
                'scope' => 'published',
                'filters' => [
                    'category_id' => 'auto',   // auto = current category, or specific ID, or 'all'
                    'parent_id' => 'auto',     // auto = sub-services of current service if applicable
                ],
                'limit' => 8,
                'order_by' => 'sort_order',
                'order_dir' => 'asc',
                'manual_ids' => [],
                'with' => ['category', 'heroMedia'],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'layout' => 'grid',
                'columns' => '3',
                'variant' => 'architectural',
                'show_icon' => true,
                'show_divider' => false,
                'show_usp_list' => false,
                'card_cta_label' => 'Details',
                'tone' => 'light',
                'show_category_nav' => true,
                'show_view_all' => true,
                'view_all_text' => 'View All Services',
                'view_all_url' => '/services',
            ],
        ],

        'service_categories' => [
            'label' => 'Service Categories',
            'icon' => 'layers',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'list' => 'List'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['editorial' => 'Editorial', 'minimal' => 'Minimal'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
                ['key' => 'show_service_preview', 'label' => 'Show Service Preview Links', 'type' => 'toggle'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\ServiceCategory',
                'scope' => 'published',
                'filters' => [
                    'parent_id' => 'auto',     // auto = sub-categories of current category, or null for top-level
                ],
                'limit' => 10,
                'order_by' => 'sort_order',
                'order_dir' => 'asc',
                'manual_ids' => [],
                'with' => [
                    'services' => [
                        'where' => ['status' => 'published'],
                        'orderBy' => 'sort_order',
                    ],
                ],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'layout' => 'grid',
                'variant' => 'editorial',
                'tone' => 'light',
                'show_service_preview' => true,
            ],
        ],

        'testimonials' => [
            'label' => 'Testimonials / Reviews',
            'icon' => 'star',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'slider' => 'Slider'],
                ],
                ['key' => 'featured_only', 'label' => 'Featured Only', 'type' => 'toggle'],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['editorial' => 'Editorial', 'compact' => 'Compact', 'highlight' => 'Highlight'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['cream' => 'Cream', 'light' => 'Light', 'dark' => 'Dark'],
                ],
            ],
            'data_source' => [
                'model' => 'App\\Models\\Review',
                'scope' => 'published',
                'filters' => [
                    'city_relevance' => 'auto',  // auto = from page context
                    'is_featured' => true,
                ],
                'limit' => 9,
                'order_by' => 'review_date',
                'order_dir' => 'desc',
                'manual_ids' => [],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'layout' => 'grid',
                'featured_only' => true,
                'variant' => 'editorial',
                'tone' => 'cream',
            ],
        ],

        'portfolio_gallery' => [
            'label' => 'Portfolio Gallery',
            'icon' => 'image',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'masonry' => 'Masonry', 'slider' => 'Slider', 'rail' => 'Horizontal Rail'],
                ],
                [
                    'key' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['editorial' => 'Editorial', 'stacked' => 'Stacked', 'minimal' => 'Minimal', 'compact' => 'Compact'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'dark' => 'Dark', 'cream' => 'Cream'],
                ],
                ['key' => 'show_view_all', 'label' => 'Show View All Link', 'type' => 'toggle'],
                ['key' => 'view_all_text', 'label' => 'View All Text', 'type' => 'text'],
                ['key' => 'view_all_url', 'label' => 'View All URL', 'type' => 'text'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\PortfolioProject',
                'scope' => 'published',
                'filters' => [
                    'category_id' => 'auto',
                    'city_id' => 'auto',
                    'service_id' => 'auto',
                ],
                'limit' => 6,
                'order_by' => 'completion_date',
                'order_dir' => 'desc',
                'manual_ids' => [],
                'with' => ['heroMedia', 'city', 'service'],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'layout' => 'grid',
                'columns' => '3',
                'variant' => 'editorial',
                'tone' => 'light',
                'show_view_all' => true,
                'view_all_text' => 'View All Projects',
                'view_all_url' => '/portfolio',
            ],
        ],

        'portfolio_directory' => [
            'label' => 'Portfolio Directory',
            'icon' => 'gallery-horizontal',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
                ['key' => 'show_filters', 'label' => 'Show Filter Controls', 'type' => 'toggle'],
                ['key' => 'show_featured_hero', 'label' => 'Show Featured Hero Card', 'type' => 'toggle'],
                ['key' => 'show_category_pills', 'label' => 'Show Category Pills', 'type' => 'toggle'],
                ['key' => 'empty_title', 'label' => 'Empty State Title', 'type' => 'text'],
                ['key' => 'empty_description', 'label' => 'Empty State Description', 'type' => 'textarea'],
                ['key' => 'empty_button_text', 'label' => 'Empty State Button Text', 'type' => 'text'],
                ['key' => 'empty_button_url', 'label' => 'Empty State Button URL', 'type' => 'text'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\PortfolioProject',
                'scope' => 'published',
                'filters' => [
                    'category_id' => 'auto',
                    'city_id' => 'auto',
                ],
                'limit' => 12,
                'order_by' => 'completion_date',
                'order_dir' => 'desc',
                'manual_ids' => [],
                'with' => ['heroMedia', 'city', 'service', 'category'],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => 'Our Project Portfolio',
                'subtitle' => 'Real projects, real results. Browse our completed landscaping work across Ontario.',
                'tone' => 'light',
                'show_filters' => true,
                'show_featured_hero' => true,
                'show_category_pills' => true,
                'empty_title' => 'No projects found',
                'empty_description' => 'Try adjusting your filters or browse all projects.',
                'empty_button_text' => 'View All Projects',
                'empty_button_url' => '/portfolio',
            ],
        ],

        'faq_directory' => [
            'label' => 'FAQ Directory',
            'icon' => 'help-circle',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => 'Help & Support',
                'heading' => 'Frequently Asked Questions',
                'subtitle' => 'Browse our FAQ categories below or use the search to find specific information.',
                'tone' => 'light',
            ],
        ],

        'faq_section' => [
            'label' => 'FAQs',
            'icon' => 'help-circle',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['accordion' => 'Accordion', 'list' => 'List'],
                ],
            ],
            'data_source' => [
                'model' => 'App\\Models\\Faq',
                'scope' => 'published',
                'filters' => [
                    'category_id' => null,
                    'city_relevance' => 'auto',
                    'is_featured' => false,
                ],
                'limit' => 6,
                'order_by' => 'display_order',
                'order_dir' => 'asc',
                'manual_ids' => [],
                'with' => ['category'],
            ],
            'defaults' => [
                'heading' => '',
                'subtitle' => '',
                'style' => 'accordion',
            ],
        ],

        'blog_strip' => [
            'label' => 'Blog / News Strip',
            'icon' => 'newspaper',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'slider' => 'Slider'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['editorial' => 'Editorial', 'minimal' => 'Minimal'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
                ['key' => 'show_view_all', 'label' => 'Show View All Link', 'type' => 'toggle'],
                ['key' => 'view_all_text', 'label' => 'View All Text', 'type' => 'text'],
                ['key' => 'view_all_url', 'label' => 'View All URL', 'type' => 'text'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\BlogPost',
                'scope' => 'published',
                'filters' => [
                    'category_id' => 'auto',
                ],
                'limit' => 3,
                'order_by' => 'published_at',
                'order_dir' => 'desc',
                'manual_ids' => [],
                'with' => ['heroMedia', 'category'],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'layout' => 'grid',
                'variant' => 'editorial',
                'tone' => 'light',
                'show_view_all' => true,
                'view_all_text' => 'View All Posts',
                'view_all_url' => '/blog',
            ],
        ],

        'blog_directory' => [
            'label' => 'Blog Directory',
            'icon' => 'newspaper',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
                ['key' => 'show_featured_hero', 'label' => 'Show Featured Hero Article', 'type' => 'toggle'],
                ['key' => 'show_category_tabs', 'label' => 'Show Category Tabs', 'type' => 'toggle'],
                ['key' => 'empty_title', 'label' => 'Empty State Title', 'type' => 'text'],
                ['key' => 'empty_description', 'label' => 'Empty State Description', 'type' => 'textarea'],
                ['key' => 'empty_button_text', 'label' => 'Empty State Button Text', 'type' => 'text'],
                ['key' => 'empty_button_url', 'label' => 'Empty State Button URL', 'type' => 'text'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\BlogPost',
                'scope' => 'published',
                'filters' => [
                    'category_id' => 'auto',
                ],
                'limit' => 12,
                'order_by' => 'published_at',
                'order_dir' => 'desc',
                'manual_ids' => [],
                'with' => ['heroMedia', 'category', 'author'],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => 'Landscaping Blog',
                'subtitle' => 'Expert tips, cost guides, and project inspiration for Ontario homeowners.',
                'tone' => 'light',
                'show_featured_hero' => true,
                'show_category_tabs' => true,
                'empty_title' => 'No articles published yet',
                'empty_description' => 'We are preparing expert guidance, project insights, and planning articles for Ontario homeowners.',
                'empty_button_text' => 'Back to Home',
                'empty_button_url' => '/',
            ],
        ],

        'city_grid' => [
            'label' => 'Service Areas Grid',
            'icon' => 'map-pin',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'list' => 'List', 'strip' => 'City Strip', 'compact' => 'Compact Cards'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'dark' => 'Dark', 'cream' => 'Cream'],
                ],
                ['key' => 'show_view_all', 'label' => 'Show View All Link', 'type' => 'toggle'],
                ['key' => 'view_all_text', 'label' => 'View All Text', 'type' => 'text'],
                ['key' => 'view_all_url', 'label' => 'View All URL', 'type' => 'text'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\City',
                'scope' => 'published',
                'filters' => [],
                'limit' => 16,
                'order_by' => 'sort_order',
                'order_dir' => 'asc',
                'manual_ids' => [],
                'with' => [
                    'neighborhoods' => [
                        'where' => ['status' => 'published'],
                        'orderBy' => 'sort_order',
                        'limit' => 3,
                    ],
                ],
            ],
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'layout' => 'grid',
                'tone' => 'light',
                'show_view_all' => true,
                'view_all_text' => 'View All Areas',
                'view_all_url' => '/locations',
            ],
        ],

        'stats_bar' => [
            'label' => 'Trust Stats Bar',
            'icon' => 'bar-chart-2',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['metrics' => 'Metrics', 'trust_band' => 'Trust Band', 'hero_panel' => 'Hero Panel'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'dark' => 'Dark', 'forest' => 'Forest'],
                ],
                [
                    'key' => 'stats',
                    'label' => 'Stats',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'number', 'label' => 'Number', 'type' => 'text'],
                        ['key' => 'label', 'label' => 'Label', 'type' => 'text'],
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                    ],
                ],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'variant' => 'metrics',
                'tone' => 'light',
                'stats' => [
                    ['number' => '10+', 'label' => 'Years Experience', 'icon' => 'award'],
                    ['number' => '500+', 'label' => 'Projects Completed', 'icon' => 'check-circle'],
                    ['number' => '10', 'label' => 'Year Warranty', 'icon' => 'shield-check'],
                    ['number' => '100%', 'label' => 'Satisfaction Rate', 'icon' => 'heart'],
                ],
            ],
        ],

        'process_steps' => [
            'label' => 'Process Steps',
            'icon' => 'list-ordered',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['numbered' => 'Numbered', 'feature_rows' => 'Feature Rows', 'timeline' => 'Timeline', 'premium-stack' => 'Premium Stack'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
                [
                    'key' => 'steps',
                    'label' => 'Steps',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Step Title', 'type' => 'text'],
                        ['key' => 'desc', 'label' => 'Description', 'type' => 'textarea'],
                    ],
                ],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => '',
                'heading' => 'Our Process',
                'subtitle' => '',
                'variant' => 'numbered',
                'tone' => 'light',
                'steps' => [
                    ['icon' => 'phone', 'title' => 'On-Site Consultation', 'desc' => 'We visit your property, assess your space, and align on goals and constraints.'],
                    ['icon' => 'file-text', 'title' => 'Scope & Proposal', 'desc' => 'Clear scope plan and material direction for your review.'],
                    ['icon' => 'hard-hat', 'title' => 'Expert Installation', 'desc' => 'Certified crew handles every detail with precision.'],
                    ['icon' => 'shield-check', 'title' => '10-Year Warranty', 'desc' => 'Backed by our industry-leading workmanship guarantee.'],
                ],
            ],
        ],

        'cta_section' => [
            'label' => 'CTA Banner',
            'icon' => 'megaphone',
            'category' => 'data',
            'governance' => [
                'required_fields' => ['title', 'button_text', 'button_url'],
                'variants' => [
                    'panel' => [
                        'label' => 'Panel',
                        'visible_fields' => ['eyebrow', 'title', 'subtitle', 'variant', 'tone', 'button_text', 'button_url'],
                    ],
                    'split' => [
                        'label' => 'Split',
                        'visible_fields' => ['eyebrow', 'title', 'subtitle', 'variant', 'tone', 'button_text', 'button_url', 'button_secondary_text', 'button_secondary_url'],
                    ],
                    'inline' => [
                        'label' => 'Inline',
                        'visible_fields' => ['title', 'variant', 'tone', 'button_text', 'button_url'],
                    ],
                ],
            ],
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['panel' => 'Panel', 'split' => 'Split', 'inline' => 'Inline'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark', 'forest' => 'Forest'],
                ],
                ['key' => 'button_text', 'label' => 'Button Text', 'type' => 'text'],
                ['key' => 'button_url', 'label' => 'Button URL', 'type' => 'text'],
                ['key' => 'button_secondary_text', 'label' => 'Secondary Button Text', 'type' => 'text'],
                ['key' => 'button_secondary_url', 'label' => 'Secondary Button URL', 'type' => 'text'],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => '',
                'title' => '',
                'subtitle' => '',
                'variant' => 'panel',
                'tone' => 'cream',
                'button_text' => 'Book a Consultation',
                'button_url' => '/consultation',
                'button_secondary_text' => '',
                'button_secondary_url' => '',
            ],
        ],

        'trust_badges' => [
            'label' => 'Trust Badges',
            'icon' => 'shield-check',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'compact' => 'Compact', 'cards' => 'Cards'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'dark' => 'Dark'],
                ],
                [
                    'key' => 'badges',
                    'label' => 'Badges',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'desc', 'label' => 'Description', 'type' => 'text'],
                    ],
                ],
            ],
            'data_source' => null,
            'defaults' => [
                'eyebrow' => '',
                'heading' => '',
                'subtitle' => '',
                'variant' => 'grid',
                'tone' => 'light',
                'badges' => [
                    ['icon' => 'shield-check', 'title' => 'Licensed & Insured', 'desc' => 'Fully licensed and insured for your peace of mind.'],
                    ['icon' => 'award', 'title' => '10-Year Warranty', 'desc' => 'Industry-leading workmanship guarantee.'],
                    ['icon' => 'clock', 'title' => 'On-Time Delivery', 'desc' => 'We complete projects on schedule, every time.'],
                    ['icon' => 'leaf', 'title' => 'Premium Materials', 'desc' => 'Only the highest quality materials sourced responsibly.'],
                ],
            ],
        ],

        'local_about' => [
            'label' => 'About & Neighbourhoods',
            'icon' => 'map-pin',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\Neighborhood',
                'scope' => 'published',
                'filters' => ['city_id' => 'auto'],
                'limit' => 10,
                'order_by' => 'sort_order',
                'order_dir' => 'asc',
            ],
            'defaults' => ['heading' => '', 'subtitle' => ''],
        ],

        'city_availability' => [
            'label' => 'Cities We Serve',
            'icon' => 'map',
            'category' => 'data',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Section Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Section Subtitle', 'type' => 'textarea'],
            ],
            'data_source' => [
                'model' => 'App\\Models\\City',
                'scope' => 'published',
                'filters' => [],
                'limit' => 20,
                'order_by' => 'name',
                'order_dir' => 'asc',
            ],
            'defaults' => ['heading' => '', 'subtitle' => ''],
        ],

        // =====================================================================
        // CONTENT BLOCKS — Static content
        // =====================================================================

        'heading' => [
            'label' => 'Heading',
            'icon' => 'heading',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'text', 'label' => 'Heading Text', 'type' => 'text'],
                [
                    'key' => 'level',
                    'label' => 'Level',
                    'type' => 'select',
                    'options' => ['h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5', 'h6' => 'H6'],
                ],
                [
                    'key' => 'align',
                    'label' => 'Alignment',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                ],
            ],
            'defaults' => ['text' => '', 'level' => 'h2', 'align' => 'left'],
        ],

        'paragraph' => [
            'label' => 'Paragraph',
            'icon' => 'align-left',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'text', 'label' => 'Text', 'type' => 'textarea'],
            ],
            'defaults' => ['text' => ''],
        ],

        'rich_text' => [
            'label' => 'Rich Text',
            'icon' => 'file-text',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'html', 'label' => 'Content (HTML)', 'type' => 'richtext'],
            ],
            'defaults' => ['html' => ''],
        ],

        'section_header' => [
            'label' => 'Section Header',
            'icon' => 'type',
            'category' => 'content',
            'governance' => [
                'required_fields' => ['heading'],
            ],
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                ['key' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'],
                ['key' => 'cta_url', 'label' => 'CTA URL', 'type' => 'text'],
                [
                    'key' => 'align',
                    'label' => 'Alignment',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center'],
                ],
                ['key' => 'tag', 'label' => 'Tag/Label', 'type' => 'text'],
                ['key' => 'show_line', 'label' => 'Show Decorative Line', 'type' => 'toggle'],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => [
                        'editorial' => 'Editorial',
                        'full-editorial' => 'Full Editorial',
                        'split' => 'Split Intro',
                        'compact' => 'Compact',
                        'title-only' => 'Title Only',
                        'with-right-cta' => 'Title + Right CTA',
                    ],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['forest' => 'Forest', 'dark' => 'Dark', 'light' => 'Light'],
                ],
                [
                    'key' => 'width',
                    'label' => 'Content Width',
                    'type' => 'select',
                    'options' => ['md' => 'Narrow', 'lg' => 'Medium', 'xl' => 'Wide'],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'subtitle' => '',
                'cta_text' => '',
                'cta_url' => '',
                'align' => 'center',
                'tag' => '',
                'show_line' => true,
                'variant' => 'editorial',
                'tone' => 'forest',
                'width' => 'lg',
            ],
        ],

        'cards_grid' => [
            'label' => 'Cards Grid',
            'icon' => 'layout-grid',
            'category' => 'content',
            'governance' => [
                'required_fields' => ['cards'],
            ],
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                [
                    'key' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Card Variant',
                    'type' => 'select',
                    'options' => ['editorial' => 'Editorial', 'minimal' => 'Minimal', 'icon' => 'Icon Focused'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'forest' => 'Forest'],
                ],
                [
                    'key' => 'cards',
                    'label' => 'Cards',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'meta', 'label' => 'Meta / Eyebrow', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'media_id', 'label' => 'Image', 'type' => 'media'],
                        ['key' => 'link_text', 'label' => 'Link Text', 'type' => 'text'],
                        ['key' => 'link_url', 'label' => 'Link URL', 'type' => 'text'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'subtitle' => '',
                'eyebrow' => '',
                'columns' => '3',
                'variant' => 'editorial',
                'tone' => 'light',
                'cards' => [],
            ],
        ],

        'template_card_shell' => [
            'label' => 'Template Card Shell',
            'icon' => 'credit-card',
            'category' => 'content',
            'governance' => [
                'allowed_page_types' => ['template_card'],
                'required_fields' => ['title'],
            ],
            'content_fields' => [
                ['key' => 'image_url', 'label' => 'Image URL (supports variables)', 'type' => 'text'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                ['key' => 'subtitle', 'label' => 'Subtitle', 'type' => 'textarea'],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'cream' => 'Cream', 'forest' => 'Forest', 'dark' => 'Dark'],
                ],
                [
                    'key' => 'image_ratio',
                    'label' => 'Image Ratio',
                    'type' => 'select',
                    'options' => ['4:3' => '4:3', '16:9' => '16:9', '1:1' => '1:1', '3:2' => '3:2'],
                ],
                [
                    'key' => 'show_cta',
                    'label' => 'Show CTA',
                    'type' => 'toggle',
                ],
                ['key' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'],
                ['key' => 'cta_url', 'label' => 'CTA URL (supports variables)', 'type' => 'text'],
            ],
            'defaults' => [
                'image_url' => '',
                'eyebrow' => '',
                'title' => '',
                'subtitle' => '',
                'tone' => 'light',
                'image_ratio' => '4:3',
                'show_cta' => false,
                'cta_text' => 'View',
                'cta_url' => '{item.url}',
            ],
        ],

        'image_text' => [
            'label' => 'Image + Text',
            'icon' => 'panel-left',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'media_id', 'label' => 'Image', 'type' => 'media'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'text', 'label' => 'Body (HTML)', 'type' => 'richtext'],
                ['key' => 'button_text', 'label' => 'Button Text', 'type' => 'text'],
                ['key' => 'button_url', 'label' => 'Button URL', 'type' => 'text'],
                [
                    'key' => 'image_side',
                    'label' => 'Image Side',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'right' => 'Right'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['editorial' => 'Editorial', 'panel' => 'Panel', 'overlap' => 'Overlap'],
                ],
                [
                    'key' => 'media_ratio',
                    'label' => 'Image Ratio',
                    'type' => 'select',
                    'options' => ['4:3' => '4:3', '3:4' => '3:4', '16:9' => '16:9', '1:1' => '1:1'],
                ],
            ],
            'defaults' => [
                'media_id' => null,
                'eyebrow' => '',
                'heading' => '',
                'text' => '',
                'button_text' => '',
                'button_url' => '',
                'image_side' => 'left',
                'variant' => 'editorial',
                'media_ratio' => '4:3',
            ],
        ],

        'feature_list' => [
            'label' => 'Feature List',
            'icon' => 'list-checks',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                [
                    'key' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => ['1' => '1', '2' => '2'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['editorial' => 'Editorial', 'minimal' => 'Minimal'],
                ],
                [
                    'key' => 'features',
                    'label' => 'Features',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'eyebrow' => '',
                'columns' => '2',
                'variant' => 'editorial',
                'features' => [],
            ],
        ],
        'area_served' => [
            'label' => 'Areas Served',
            'icon' => 'map-pin',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'inline' => 'Inline Text'],
                ],
                [
                    'key' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4'],
                ],
                [
                    'key' => 'areas',
                    'label' => 'Areas',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'name', 'label' => 'Name', 'type' => 'text'],
                        ['key' => 'url', 'label' => 'URL', 'type' => 'text'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'description' => '',
                'layout' => 'grid',
                'columns' => '3',
                'areas' => [],
            ],
        ],
        'number_counter' => [
            'label' => 'Number Counter',
            'icon' => 'hash',
            'category' => 'content',
            'content_fields' => [
                [
                    'key' => 'bg',
                    'label' => 'Background',
                    'type' => 'select',
                    'options' => [
                        'white' => 'White',
                        'cream' => 'Cream',
                        'forest' => 'Forest',
                        'dark' => 'Dark',
                    ],
                ],
                [
                    'key' => 'counters',
                    'label' => 'Counters',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'target', 'label' => 'Target', 'type' => 'text'],
                        ['key' => 'suffix', 'label' => 'Suffix', 'type' => 'text'],
                        ['key' => 'label', 'label' => 'Label', 'type' => 'text'],
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'text'],
                    ],
                ],
            ],
            'defaults' => [
                'bg' => 'white',
                'counters' => [],
            ],
        ],
        'interactive_map' => [
            'label' => 'Interactive Map',
            'icon' => 'map',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                [
                    'key' => 'map_mode',
                    'label' => 'Mode',
                    'type' => 'select',
                    'options' => [
                        'all_cities' => 'All Cities',
                        'single_city' => 'Single City',
                    ],
                ],
                ['key' => 'city_slug', 'label' => 'City Slug (single_city)', 'type' => 'text'],
                ['key' => 'center_lat', 'label' => 'Center Lat', 'type' => 'text'],
                ['key' => 'center_lng', 'label' => 'Center Lng', 'type' => 'text'],
                ['key' => 'zoom', 'label' => 'Zoom', 'type' => 'text'],
                ['key' => 'height', 'label' => 'Height (px)', 'type' => 'text'],
                ['key' => 'show_chips', 'label' => 'Show Filters', 'type' => 'boolean'],
                [
                    'key' => 'marker_color',
                    'label' => 'Marker Color',
                    'type' => 'select',
                    'options' => [
                        'forest' => 'Forest',
                        'accent' => 'Accent',
                        'blue' => 'Blue',
                        'red' => 'Red',
                    ],
                ],
                ['key' => 'popup_cta_text', 'label' => 'Popup CTA Text', 'type' => 'text'],
                ['key' => 'schema_type', 'label' => 'Schema Type', 'type' => 'text'],
                [
                    'key' => 'markers',
                    'label' => 'Custom Markers',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'name', 'label' => 'Name', 'type' => 'text'],
                        ['key' => 'lat', 'label' => 'Lat', 'type' => 'text'],
                        ['key' => 'lng', 'label' => 'Lng', 'type' => 'text'],
                        ['key' => 'popup_heading', 'label' => 'Popup Heading', 'type' => 'text'],
                        ['key' => 'popup_description', 'label' => 'Popup Description', 'type' => 'textarea'],
                        ['key' => 'popup_cta_text', 'label' => 'Popup CTA Text', 'type' => 'text'],
                        ['key' => 'popup_cta_url', 'label' => 'Popup CTA URL', 'type' => 'text'],
                        ['key' => 'popup_services', 'label' => 'Popup Services', 'type' => 'text'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'description' => '',
                'map_mode' => 'all_cities',
                'city_slug' => '',
                'center_lat' => '43.55',
                'center_lng' => '-79.65',
                'zoom' => '9',
                'height' => '500',
                'show_chips' => true,
                'marker_color' => 'forest',
                'popup_cta_text' => 'Book a Consultation',
                'schema_type' => 'LocalBusiness',
                'markers' => [],
            ],
        ],
        'icon_grid' => [
            'label' => 'Icon Grid',
            'icon' => 'grid-2x2',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                [
                    'key' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4', '6' => '6'],
                ],
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => [
                        'card' => 'Card',
                        'circle' => 'Circle',
                        'inline' => 'Inline',
                    ],
                ],
                [
                    'key' => 'items',
                    'label' => 'Items',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'columns' => '3',
                'style' => 'card',
                'items' => [],
            ],
        ],
        'timeline' => [
            'label' => 'Timeline',
            'icon' => 'clock',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                [
                    'key' => 'items',
                    'label' => 'Items',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'date', 'label' => 'Date/Label', 'type' => 'text'],
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'items' => [],
            ],
        ],
        'steps_process' => [
            'label' => 'Steps Process',
            'icon' => 'list-ordered',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => [
                        'horizontal' => 'Horizontal',
                        'vertical' => 'Vertical',
                        'alternating' => 'Alternating',
                    ],
                ],
                [
                    'key' => 'steps',
                    'label' => 'Steps',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon', 'label' => 'Icon', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'layout' => 'horizontal',
                'steps' => [],
            ],
        ],
        'testimonial_card' => [
            'label' => 'Testimonial Card',
            'icon' => 'message-square-quote',
            'category' => 'content',
            'content_fields' => [
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => [
                        'card' => 'Card',
                        'minimal' => 'Minimal',
                        'featured' => 'Featured',
                    ],
                ],
                ['key' => 'quote', 'label' => 'Quote', 'type' => 'textarea'],
                ['key' => 'author', 'label' => 'Author', 'type' => 'text'],
                ['key' => 'role', 'label' => 'Role', 'type' => 'text'],
                ['key' => 'rating', 'label' => 'Rating', 'type' => 'text'],
                ['key' => 'media_id', 'label' => 'Author Photo', 'type' => 'media'],
            ],
            'defaults' => [
                'style' => 'card',
                'quote' => '',
                'author' => '',
                'role' => '',
                'rating' => '5',
                'media_id' => null,
            ],
        ],
        'cta_banner' => [
            'label' => 'CTA Banner (Transitional)',
            'icon' => 'megaphone',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'subheading', 'label' => 'Subheading', 'type' => 'textarea'],
                ['key' => 'button_text', 'label' => 'Button Text', 'type' => 'text'],
                ['key' => 'button_url', 'label' => 'Button URL', 'type' => 'text'],
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => [
                        'forest' => 'Forest',
                        'cream' => 'Cream',
                        'white' => 'White',
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'subheading' => '',
                'button_text' => 'Book a Consultation',
                'button_url' => '/contact',
                'style' => 'forest',
            ],
        ],
        'editorial_split_feature' => [
            'label' => 'Editorial Split Feature',
            'icon' => 'panel-left-dashed',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'media_id', 'label' => 'Feature Image', 'type' => 'media'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'forest' => 'Forest', 'dark' => 'Dark'],
                ],
                [
                    'key' => 'media_side',
                    'label' => 'Media Side',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'right' => 'Right'],
                ],
                [
                    'key' => 'media_ratio',
                    'label' => 'Media Ratio',
                    'type' => 'select',
                    'options' => ['4:5' => '4:5 Portrait', '4:3' => '4:3 Landscape', '16:9' => '16:9', '1:1' => '1:1'],
                ],
                [
                    'key' => 'ornament_style',
                    'label' => 'Media Ornament',
                    'type' => 'select',
                    'options' => ['oval' => 'Oval Ring', 'offset' => 'Offset Panel', 'none' => 'None'],
                ],
                [
                    'key' => 'feature_layout',
                    'label' => 'Feature Layout',
                    'type' => 'select',
                    'options' => ['stacked' => 'Stacked Rows', 'cards' => 'Feature Cards'],
                ],
                [
                    'key' => 'features',
                    'label' => 'Feature Rows',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                    ],
                ],
                ['key' => 'cta_text', 'label' => 'CTA Text', 'type' => 'text'],
                ['key' => 'cta_url', 'label' => 'CTA URL', 'type' => 'text'],
            ],
            'defaults' => [
                'media_id' => null,
                'eyebrow' => '',
                'heading' => '',
                'description' => '',
                'tone' => 'light',
                'media_side' => 'left',
                'media_ratio' => '4:5',
                'ornament_style' => 'oval',
                'feature_layout' => 'stacked',
                'features' => [],
                'cta_text' => '',
                'cta_url' => '',
            ],
        ],

        'blockquote' => [
            'label' => 'Blockquote',
            'icon' => 'quote',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'text', 'label' => 'Quote Text', 'type' => 'textarea'],
                ['key' => 'author', 'label' => 'Author/Source', 'type' => 'text'],
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['bordered' => 'Left Border', 'card' => 'Card', 'large' => 'Large Centered'],
                ],
            ],
            'defaults' => ['text' => '', 'author' => '', 'style' => 'bordered'],
        ],

        'alert_box' => [
            'label' => 'Alert / Notice Box',
            'icon' => 'alert-circle',
            'category' => 'content',
            'content_fields' => [
                ['key' => 'text', 'label' => 'Message', 'type' => 'textarea'],
                ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                [
                    'key' => 'type',
                    'label' => 'Type',
                    'type' => 'select',
                    'options' => ['info' => 'Info', 'success' => 'Success', 'warning' => 'Warning', 'error' => 'Error', 'tip' => 'Pro Tip'],
                ],
                ['key' => 'dismissible', 'label' => 'Dismissible', 'type' => 'toggle'],
            ],
            'defaults' => ['text' => '', 'title' => '', 'type' => 'info', 'dismissible' => false],
        ],

        // =====================================================================
        // LAYOUT BLOCKS
        // =====================================================================

        'two_column' => [
            'label' => 'Two Column',
            'icon' => 'columns',
            'category' => 'layout',
            'governance' => [
                'supports_children_rules' => [
                    'slot_key' => '_layout_slot',
                    'allowed_slots' => ['left', 'right'],
                    'required_slots' => ['left', 'right'],
                ],
            ],
            'content_fields' => [
                ['key' => 'left_html', 'label' => 'Left Column Content (HTML)', 'type' => 'textarea'],
                ['key' => 'right_html', 'label' => 'Right Column Content (HTML)', 'type' => 'textarea'],
                [
                    'key' => 'ratio',
                    'label' => 'Column Ratio',
                    'type' => 'select',
                    'options' => ['1:1' => '50/50', '1:2' => '33/67', '2:1' => '67/33'],
                ],
                ['key' => 'reverse_mobile', 'label' => 'Reverse on Mobile', 'type' => 'toggle'],
                [
                    'key' => 'gap',
                    'label' => 'Gap Size',
                    'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
                ],
            ],
            'supports_children' => true,
            'defaults' => ['left_html' => '', 'right_html' => '', 'ratio' => '1:1', 'reverse_mobile' => false, 'gap' => 'md'],
        ],

        'three_column' => [
            'label' => 'Three Column',
            'icon' => 'columns-3',
            'category' => 'layout',
            'governance' => [
                'supports_children_rules' => [
                    'slot_key' => '_layout_slot',
                    'allowed_slots' => ['col1', 'col2', 'col3'],
                    'required_slots' => ['col1', 'col2', 'col3'],
                ],
            ],
            'content_fields' => [
                ['key' => 'col1_html', 'label' => 'Column 1 Content (HTML)', 'type' => 'textarea'],
                ['key' => 'col2_html', 'label' => 'Column 2 Content (HTML)', 'type' => 'textarea'],
                ['key' => 'col3_html', 'label' => 'Column 3 Content (HTML)', 'type' => 'textarea'],
                [
                    'key' => 'gap',
                    'label' => 'Gap Size',
                    'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
                ],
            ],
            'supports_children' => true,
            'defaults' => ['col1_html' => '', 'col2_html' => '', 'col3_html' => '', 'gap' => 'md'],
        ],

        'tabs' => [
            'label' => 'Tabs',
            'icon' => 'folder-open',
            'category' => 'layout',
            'content_fields' => [
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['underline' => 'Underline', 'pills' => 'Pills', 'boxed' => 'Boxed'],
                ],
                [
                    'key' => 'tabs',
                    'label' => 'Tab Panels',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'title', 'label' => 'Tab Title', 'type' => 'text'],
                        ['key' => 'content', 'label' => 'Content (HTML)', 'type' => 'richtext'],
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                    ],
                ],
            ],
            'defaults' => ['style' => 'underline', 'tabs' => []],
        ],

        'accordion' => [
            'label' => 'Accordion',
            'icon' => 'list-collapse',
            'category' => 'layout',
            'content_fields' => [
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['default' => 'Default', 'bordered' => 'Bordered', 'minimal' => 'Minimal'],
                ],
                [
                    'key' => 'items',
                    'label' => 'Accordion Items',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'title', 'label' => 'Title', 'type' => 'text'],
                        ['key' => 'content', 'label' => 'Content (HTML)', 'type' => 'richtext'],
                        ['key' => 'open', 'label' => 'Open by Default', 'type' => 'toggle'],
                    ],
                ],
            ],
            'defaults' => ['style' => 'default', 'items' => []],
        ],

        'divider' => [
            'label' => 'Divider / Separator',
            'icon' => 'minus',
            'category' => 'layout',
            'content_fields' => [
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['solid' => 'Solid', 'dashed' => 'Dashed', 'dotted' => 'Dotted', 'gradient' => 'Gradient'],
                ],
                [
                    'key' => 'width',
                    'label' => 'Width',
                    'type' => 'select',
                    'options' => ['full' => 'Full', 'centered' => 'Centered'],
                ],
            ],
            'defaults' => ['style' => 'solid', 'width' => 'full'],
        ],

        'spacer' => [
            'label' => 'Spacer',
            'icon' => 'move-vertical',
            'category' => 'layout',
            'content_fields' => [
                [
                    'key' => 'height',
                    'label' => 'Height',
                    'type' => 'select',
                    'options' => ['xs' => 'Extra Small', 'sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large', 'xl' => 'Extra Large'],
                ],
            ],
            'defaults' => ['height' => 'md'],
        ],

        // =====================================================================
        // MEDIA BLOCKS
        // =====================================================================

        'image' => [
            'label' => 'Image',
            'icon' => 'image',
            'category' => 'media',
            'content_fields' => [
                ['key' => 'media_id', 'label' => 'Image', 'type' => 'media'],
                ['key' => 'alt', 'label' => 'Alt Text', 'type' => 'text'],
                ['key' => 'caption', 'label' => 'Caption', 'type' => 'text'],
                ['key' => 'link_url', 'label' => 'Link URL', 'type' => 'text'],
                [
                    'key' => 'aspect_ratio',
                    'label' => 'Aspect Ratio',
                    'type' => 'select',
                    'options' => ['auto' => 'Auto', '16:9' => '16:9', '4:3' => '4:3', '1:1' => '1:1', '3:2' => '3:2'],
                ],
                ['key' => 'rounded', 'label' => 'Rounded', 'type' => 'toggle'],
            ],
            'defaults' => ['media_id' => null, 'alt' => '', 'caption' => '', 'link_url' => '', 'aspect_ratio' => 'auto', 'rounded' => false],
        ],

        'video' => [
            'label' => 'Video',
            'icon' => 'play',
            'category' => 'media',
            'content_fields' => [
                ['key' => 'url', 'label' => 'Video URL (YouTube/Vimeo)', 'type' => 'text'],
                ['key' => 'media_id', 'label' => 'Or Upload Video', 'type' => 'media'],
                ['key' => 'autoplay', 'label' => 'Autoplay', 'type' => 'toggle'],
                ['key' => 'muted', 'label' => 'Muted', 'type' => 'toggle'],
                ['key' => 'loop', 'label' => 'Loop', 'type' => 'toggle'],
                [
                    'key' => 'aspect_ratio',
                    'label' => 'Aspect Ratio',
                    'type' => 'select',
                    'options' => ['16:9' => '16:9', '4:3' => '4:3', '1:1' => '1:1'],
                ],
            ],
            'defaults' => ['url' => '', 'media_id' => null, 'autoplay' => false, 'muted' => true, 'loop' => false, 'aspect_ratio' => '16:9'],
        ],

        'gallery' => [
            'label' => 'Image Gallery',
            'icon' => 'images',
            'category' => 'media',
            'content_fields' => [
                ['key' => 'media_ids', 'label' => 'Images', 'type' => 'media_multi'],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['grid' => 'Grid', 'masonry' => 'Masonry', 'slider' => 'Slider'],
                ],
                [
                    'key' => 'columns',
                    'label' => 'Columns',
                    'type' => 'select',
                    'options' => ['2' => '2', '3' => '3', '4' => '4'],
                ],
                ['key' => 'lightbox', 'label' => 'Enable Lightbox', 'type' => 'toggle'],
            ],
            'defaults' => ['media_ids' => [], 'layout' => 'grid', 'columns' => '3', 'lightbox' => true],
        ],

        'before_after' => [
            'label' => 'Before / After',
            'icon' => 'arrow-left-right',
            'category' => 'media',
            'content_fields' => [
                ['key' => 'before_media_id', 'label' => 'Before Image', 'type' => 'media'],
                ['key' => 'after_media_id', 'label' => 'After Image', 'type' => 'media'],
                ['key' => 'caption', 'label' => 'Caption', 'type' => 'text'],
            ],
            'defaults' => ['before_media_id' => null, 'after_media_id' => null, 'caption' => ''],
        ],

        // =====================================================================
        // INTERACTIVE BLOCKS
        // =====================================================================

        'cta_button' => [
            'label' => 'CTA Button',
            'icon' => 'mouse-pointer-click',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'text', 'label' => 'Button Text', 'type' => 'text'],
                ['key' => 'url', 'label' => 'URL', 'type' => 'text'],
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['primary' => 'Primary', 'secondary' => 'Secondary', 'outline' => 'Outline', 'ghost' => 'Ghost'],
                ],
                [
                    'key' => 'size',
                    'label' => 'Size',
                    'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
                ],
                ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                [
                    'key' => 'icon_position',
                    'label' => 'Icon Position',
                    'type' => 'select',
                    'options' => ['right' => 'Right', 'left' => 'Left'],
                ],
                ['key' => 'open_new_tab', 'label' => 'Open in New Tab', 'type' => 'toggle'],
            ],
            'defaults' => ['text' => 'Learn More', 'url' => '#', 'style' => 'primary', 'size' => 'md', 'icon' => '', 'icon_position' => 'right', 'open_new_tab' => false],
        ],

        'form_block' => [
            'label' => 'Form',
            'icon' => 'file-input',
            'category' => 'interactive',
            'governance' => [
                'required_fields' => ['form_slug'],
            ],
            'content_fields' => [
                [
                    'key' => 'form_slug',
                    'label' => 'Form',
                    'type' => 'select_model',
                    'model' => 'App\\Models\\Form',
                    'label_field' => 'name',
                    'value_field' => 'slug',
                ],
                ['key' => 'show_title', 'label' => 'Show Form Title', 'type' => 'toggle'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                ['key' => 'heading', 'label' => 'Heading Override', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['minimal' => 'Minimal', 'panel' => 'Panel', 'split' => 'Split Contact Panel', 'inline' => 'Inline'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'dark' => 'Dark', 'cream' => 'Cream'],
                ],
                [
                    'key' => 'panel_style',
                    'label' => 'Panel Style',
                    'type' => 'select',
                    'options' => ['luxury' => 'Luxury Panel', 'glass' => 'Glass Panel', 'minimal' => 'Minimal'],
                ],
                [
                    'key' => 'field_style',
                    'label' => 'Field Style',
                    'type' => 'select',
                    'options' => ['luxury' => 'Luxury', 'soft' => 'Soft', 'underline' => 'Underline'],
                ],
                [
                    'key' => 'field_columns',
                    'label' => 'Field Layout',
                    'type' => 'select',
                    'options' => ['auto' => 'Use Field Widths', '1' => 'Single Column', '2' => 'Two Columns'],
                ],
                ['key' => 'submit_text', 'label' => 'Submit Button Text', 'type' => 'text'],
                ['key' => 'show_contact_details', 'label' => 'Show Contact Details Side', 'type' => 'toggle'],
                ['key' => 'contact_phone', 'label' => 'Contact Phone Override', 'type' => 'text'],
                ['key' => 'contact_email', 'label' => 'Contact Email Override', 'type' => 'text'],
                ['key' => 'contact_address', 'label' => 'Contact Address Override', 'type' => 'textarea'],
                ['key' => 'support_cta_text', 'label' => 'Support CTA Text', 'type' => 'text'],
                ['key' => 'support_cta_url', 'label' => 'Support CTA URL', 'type' => 'text'],
            ],
            'defaults' => [
                'form_slug' => 'consultation',
                'show_title' => true,
                'eyebrow' => '',
                'heading' => '',
                'description' => '',
                'variant' => 'minimal',
                'tone' => 'light',
                'panel_style' => 'luxury',
                'field_style' => 'luxury',
                'field_columns' => 'auto',
                'submit_text' => 'Submit',
                'show_contact_details' => false,
                'contact_phone' => '',
                'contact_email' => '',
                'contact_address' => '',
                'support_cta_text' => '',
                'support_cta_url' => '',
            ],
        ],

        'contact_info' => [
            'label' => 'Contact Information',
            'icon' => 'contact',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'phone', 'label' => 'Phone', 'type' => 'text'],
                ['key' => 'email', 'label' => 'Email', 'type' => 'text'],
                ['key' => 'address', 'label' => 'Address', 'type' => 'textarea'],
                ['key' => 'hours', 'label' => 'Hours', 'type' => 'textarea'],
                [
                    'key' => 'style',
                    'label' => 'Style',
                    'type' => 'select',
                    'options' => ['horizontal' => 'Horizontal', 'vertical' => 'Vertical', 'card' => 'Cards', 'panel' => 'Panel'],
                ],
                [
                    'key' => 'tone',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['light' => 'Light', 'dark' => 'Dark'],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'phone' => '',
                'email' => '',
                'address' => '',
                'hours' => '',
                'style' => 'horizontal',
                'tone' => 'light',
            ],
        ],

        'social_links' => [
            'label' => 'Social Links',
            'icon' => 'share-2',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                [
                    'key' => 'source',
                    'label' => 'Source',
                    'type' => 'select',
                    'options' => ['manual' => 'Manual', 'settings' => 'Site Settings'],
                ],
                [
                    'key' => 'align',
                    'label' => 'Alignment',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                ],
                [
                    'key' => 'size',
                    'label' => 'Size',
                    'type' => 'select',
                    'options' => ['sm' => 'Small', 'md' => 'Medium', 'lg' => 'Large'],
                ],
                [
                    'key' => 'variant',
                    'label' => 'Variant',
                    'type' => 'select',
                    'options' => ['filled' => 'Filled', 'outline' => 'Outline', 'minimal' => 'Minimal'],
                ],
                [
                    'key' => 'links',
                    'label' => 'Links',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'platform', 'label' => 'Platform', 'type' => 'text'],
                        ['key' => 'url', 'label' => 'URL', 'type' => 'text'],
                    ],
                ],
            ],
            'defaults' => [
                'heading' => '',
                'source' => 'manual',
                'align' => 'center',
                'size' => 'md',
                'variant' => 'filled',
                'links' => [],
            ],
        ],

        'newsletter_cta' => [
            'label' => 'Newsletter CTA',
            'icon' => 'mail-plus',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'heading', 'label' => 'Heading', 'type' => 'text'],
                ['key' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['key' => 'eyebrow', 'label' => 'Eyebrow', 'type' => 'text'],
                [
                    'key' => 'bg',
                    'label' => 'Tone',
                    'type' => 'select',
                    'options' => ['cream' => 'Cream', 'forest' => 'Forest', 'white' => 'White'],
                ],
                [
                    'key' => 'layout',
                    'label' => 'Layout',
                    'type' => 'select',
                    'options' => ['inline' => 'Inline', 'stacked' => 'Stacked'],
                ],
                ['key' => 'placeholder', 'label' => 'Input Placeholder', 'type' => 'text'],
                ['key' => 'button_text', 'label' => 'Button Text', 'type' => 'text'],
            ],
            'defaults' => [
                'heading' => '',
                'description' => '',
                'eyebrow' => 'Stay Updated',
                'bg' => 'cream',
                'layout' => 'inline',
                'placeholder' => 'Enter your email',
                'button_text' => 'Subscribe',
            ],
        ],

        'button_group' => [
            'label' => 'Button Group',
            'icon' => 'panel-bottom-open',
            'category' => 'interactive',
            'content_fields' => [
                [
                    'key' => 'align',
                    'label' => 'Alignment',
                    'type' => 'select',
                    'options' => ['left' => 'Left', 'center' => 'Center', 'right' => 'Right'],
                ],
                ['key' => 'stack_mobile', 'label' => 'Stack on Mobile', 'type' => 'toggle'],
                [
                    'key' => 'buttons',
                    'label' => 'Buttons',
                    'type' => 'repeater',
                    'sub_fields' => [
                        ['key' => 'text', 'label' => 'Text', 'type' => 'text'],
                        ['key' => 'url', 'label' => 'URL', 'type' => 'text'],
                        [
                            'key' => 'style',
                            'label' => 'Style',
                            'type' => 'select',
                            'options' => ['primary' => 'Primary', 'outline' => 'Outline', 'ghost' => 'Ghost'],
                        ],
                        ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                        ['key' => 'new_tab', 'label' => 'Open in New Tab', 'type' => 'toggle'],
                    ],
                ],
            ],
            'defaults' => [
                'align' => 'left',
                'stack_mobile' => false,
                'buttons' => [],
            ],
        ],

        'map' => [
            'label' => 'Google Map',
            'icon' => 'map',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'address', 'label' => 'Address', 'type' => 'text'],
                ['key' => 'zoom', 'label' => 'Zoom Level', 'type' => 'number'],
                ['key' => 'height', 'label' => 'Map Height', 'type' => 'text'],
            ],
            'defaults' => ['address' => '', 'zoom' => 12, 'height' => '400px'],
        ],

        'counter' => [
            'label' => 'Counter / Stat',
            'icon' => 'hash',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'number', 'label' => 'Number', 'type' => 'text'],
                ['key' => 'suffix', 'label' => 'Suffix (e.g. +, %, K)', 'type' => 'text'],
                ['key' => 'label', 'label' => 'Label', 'type' => 'text'],
                ['key' => 'icon', 'label' => 'Icon (Lucide)', 'type' => 'text'],
                ['key' => 'animate', 'label' => 'Animate on Scroll', 'type' => 'toggle'],
            ],
            'defaults' => ['number' => '0', 'suffix' => '', 'label' => '', 'icon' => '', 'animate' => true],
        ],

        'html_embed' => [
            'label' => 'HTML / Embed',
            'icon' => 'code',
            'category' => 'interactive',
            'content_fields' => [
                ['key' => 'html', 'label' => 'HTML Code', 'type' => 'code'],
            ],
            'defaults' => ['html' => ''],
        ],

    ],
];
