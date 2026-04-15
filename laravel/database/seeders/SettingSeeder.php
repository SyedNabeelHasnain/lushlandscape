<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name', 'value' => 'Lush Landscape Service', 'type' => 'text', 'label' => 'Site Name'],
            ['group' => 'general', 'key' => 'site_tagline', 'value' => 'Landscaping Construction Contractors in Ontario', 'type' => 'text', 'label' => 'Tagline'],
            ['group' => 'general', 'key' => 'phone', 'value' => '', 'type' => 'text', 'label' => 'Phone Number'],
            ['group' => 'general', 'key' => 'email', 'value' => 'info@lushlandscape.ca', 'type' => 'text', 'label' => 'Email'],
            ['group' => 'general', 'key' => 'address', 'value' => '', 'type' => 'textarea', 'label' => 'Address'],
            ['group' => 'general', 'key' => 'primary_city', 'value' => 'Hamilton', 'type' => 'text', 'label' => 'Primary City'],
            ['group' => 'general', 'key' => 'postal_code', 'value' => '', 'type' => 'text', 'label' => 'Postal Code'],
            ['group' => 'general', 'key' => 'warranty_years', 'value' => '10', 'type' => 'text', 'label' => 'Warranty Years'],
            ['group' => 'general', 'key' => 'founding_year', 'value' => '2018', 'type' => 'text', 'label' => 'Founding Year'],
            ['group' => 'general', 'key' => 'total_projects_count', 'value' => '500+', 'type' => 'text', 'label' => 'Total Projects (display value)'],
            ['group' => 'general', 'key' => 'business_hours_weekday', 'value' => 'Mon–Fri: 8am–6pm', 'type' => 'text', 'label' => 'Business Hours (Weekday)'],
            ['group' => 'general', 'key' => 'business_hours_weekend', 'value' => 'Sat: 9am–4pm', 'type' => 'text', 'label' => 'Business Hours (Weekend)'],
            // Trust & social proof
            ['group' => 'trust', 'key' => 'google_rating', 'value' => '4.9', 'type' => 'text', 'label' => 'Google Rating (e.g. 4.9)'],
            ['group' => 'trust', 'key' => 'google_review_count', 'value' => '0', 'type' => 'text', 'label' => 'Google Review Count'],
            ['group' => 'trust', 'key' => 'associations_text', 'value' => 'Landscape Ontario Member', 'type' => 'text', 'label' => 'Associations / Certifications (comma-separated)'],
            ['group' => 'trust', 'key' => 'urgency_message', 'value' => 'Spring 2026 booking now open. Limited project slots available.', 'type' => 'text', 'label' => 'Urgency Message (shown in hero & CTA)'],
            ['group' => 'trust', 'key' => 'hero_cta_primary', 'value' => 'Book a Consultation', 'type' => 'text', 'label' => 'Primary CTA Button Text'],
            ['group' => 'trust', 'key' => 'hero_cta_secondary', 'value' => 'View Our Work', 'type' => 'text', 'label' => 'Secondary CTA Button Text'],
            // Social
            ['group' => 'social', 'key' => 'facebook_url', 'value' => '', 'type' => 'text', 'label' => 'Facebook URL'],
            ['group' => 'social', 'key' => 'instagram_url', 'value' => '', 'type' => 'text', 'label' => 'Instagram URL'],
            ['group' => 'social', 'key' => 'google_business_url', 'value' => '', 'type' => 'text', 'label' => 'Google Business URL'],
            ['group' => 'social', 'key' => 'youtube_url', 'value' => '', 'type' => 'text', 'label' => 'YouTube URL'],
            ['group' => 'social', 'key' => 'houzz_url', 'value' => '', 'type' => 'text', 'label' => 'Houzz URL'],
            ['group' => 'social', 'key' => 'homestars_url', 'value' => '', 'type' => 'text', 'label' => 'HomeStars URL'],
            // Integrations
            ['group' => 'integrations', 'key' => 'google_analytics_id', 'value' => '', 'type' => 'text', 'label' => 'Google Analytics ID', 'is_public' => true],
            ['group' => 'integrations', 'key' => 'google_search_console', 'value' => '', 'type' => 'text', 'label' => 'Google Search Console Verification'],
            ['group' => 'integrations', 'key' => 'bing_webmaster', 'value' => '', 'type' => 'text', 'label' => 'Bing Webmaster Verification'],
            ['group' => 'integrations', 'key' => 'meta_pixel_id', 'value' => '', 'type' => 'text', 'label' => 'Meta Pixel ID'],
            ['group' => 'integrations', 'key' => 'google_maps_embed_key', 'value' => '', 'type' => 'text', 'label' => 'Google Maps Embed API Key'],
            ['group' => 'integrations', 'key' => 'google_maps_js_key', 'value' => '', 'type' => 'text', 'label' => 'Google Maps JavaScript API Key'],
            // SEO
            ['group' => 'seo', 'key' => 'default_meta_title_suffix', 'value' => 'Lush Landscape Service', 'type' => 'text', 'label' => 'Default Title Suffix'],
            ['group' => 'seo', 'key' => 'default_og_image', 'value' => '', 'type' => 'text', 'label' => 'Default OG Image Path'],
            ['group' => 'seo', 'key' => 'robots_txt_custom', 'value' => '', 'type' => 'textarea', 'label' => 'Custom robots.txt additions'],
            ['group' => 'seo', 'key' => 'twitter_handle', 'value' => '', 'type' => 'text', 'label' => 'Twitter/X Handle (e.g. @lushlandscape)'],
        ];

        // Theme & Branding
        $settings = array_merge($settings, [
            ['group' => 'theme', 'key' => 'email_logo_media_id',   'value' => '', 'type' => 'media', 'label' => 'Email Logo (falls back to Desktop Logo)'],
            ['group' => 'theme', 'key' => 'logo_desktop_media_id', 'value' => '', 'type' => 'media', 'label' => 'Desktop Logo'],
            ['group' => 'theme', 'key' => 'logo_mobile_media_id',  'value' => '', 'type' => 'media', 'label' => 'Mobile Logo'],
            ['group' => 'theme', 'key' => 'favicon_media_id',      'value' => '', 'type' => 'media', 'label' => 'Favicon'],
            ['group' => 'theme', 'key' => 'theme_primary_color',    'value' => '#1E4A2D', 'type' => 'color', 'label' => 'Primary Color'],
            ['group' => 'theme', 'key' => 'theme_primary_light',    'value' => '#2E5A3D', 'type' => 'color', 'label' => 'Primary Light'],
            ['group' => 'theme', 'key' => 'theme_primary_dark',     'value' => '#163823', 'type' => 'color', 'label' => 'Primary Dark'],
            ['group' => 'theme', 'key' => 'theme_accent_color',     'value' => '#A47148', 'type' => 'color', 'label' => 'Accent Color'],
            ['group' => 'theme', 'key' => 'theme_text_color',       'value' => '#111111', 'type' => 'color', 'label' => 'Text Color'],
            ['group' => 'theme', 'key' => 'theme_text_secondary',   'value' => '#555555', 'type' => 'color', 'label' => 'Secondary Text Color'],
            ['group' => 'theme', 'key' => 'theme_text_on_light',    'value' => '#111111', 'type' => 'color', 'label' => 'Text on Light Surfaces'],
            ['group' => 'theme', 'key' => 'theme_text_on_dark',     'value' => '#FFFFFF', 'type' => 'color', 'label' => 'Text on Dark Surfaces'],
            ['group' => 'theme', 'key' => 'theme_bg_primary',       'value' => '#FFFFFF', 'type' => 'color', 'label' => 'Primary Background Color'],
            ['group' => 'theme', 'key' => 'theme_bg_secondary',     'value' => '#FAF7F2', 'type' => 'color', 'label' => 'Secondary Background Color'],
            ['group' => 'theme', 'key' => 'theme_bg_dark',          'value' => '#153823', 'type' => 'color', 'label' => 'Dark Background Color'],
            ['group' => 'theme', 'key' => 'theme_border_color',     'value' => '#DADDD8', 'type' => 'color', 'label' => 'Border / Divider Color'],
            ['group' => 'theme', 'key' => 'theme_link_color',       'value' => '#1E4A2D', 'type' => 'color', 'label' => 'Link Color'],
            ['group' => 'theme', 'key' => 'theme_link_hover_color', 'value' => '#A47148', 'type' => 'color', 'label' => 'Link Hover Color'],
            ['group' => 'theme', 'key' => 'theme_surface_gradient_start', 'value' => 'rgba(21, 56, 35, 0.60)', 'type' => 'text', 'label' => 'Section Gradient Start'],
            ['group' => 'theme', 'key' => 'theme_surface_gradient_end', 'value' => 'rgba(21, 56, 35, 0.40)', 'type' => 'text', 'label' => 'Section Gradient End'],
            ['group' => 'theme', 'key' => 'theme_surface_gradient_deep_start', 'value' => 'rgba(21, 56, 35, 0.92)', 'type' => 'text', 'label' => 'Deep Gradient Start'],
            ['group' => 'theme', 'key' => 'theme_surface_gradient_deep_end', 'value' => 'rgba(22, 56, 35, 0.78)', 'type' => 'text', 'label' => 'Deep Gradient End'],
            ['group' => 'theme', 'key' => 'theme_heading_font',     'value' => 'Playfair Display', 'type' => 'text', 'label' => 'Heading Font Family'],
            ['group' => 'theme', 'key' => 'theme_body_font',        'value' => 'Inter', 'type' => 'text', 'label' => 'Body Font Family'],
            ['group' => 'theme', 'key' => 'theme_base_size',        'value' => 'default', 'type' => 'text', 'label' => 'Base Font Size'],
            ['group' => 'theme', 'key' => 'theme_heading_scale',    'value' => 'default', 'type' => 'text', 'label' => 'Heading Scale'],
            ['group' => 'theme', 'key' => 'theme_letter_spacing',   'value' => 'luxury', 'type' => 'text', 'label' => 'Letter Spacing Preset'],
            ['group' => 'theme', 'key' => 'theme_font_weight_base', 'value' => 'normal', 'type' => 'text', 'label' => 'Base Font Weight'],
            ['group' => 'theme', 'key' => 'theme_uppercase_ui',     'value' => '1', 'type' => 'text', 'label' => 'Uppercase Labels & Nav'],
            ['group' => 'theme', 'key' => 'theme_line_height',      'value' => 'comfortable', 'type' => 'text', 'label' => 'Line Height'],
            ['group' => 'theme', 'key' => 'theme_btn_primary_bg',   'value' => '#1E4A2D', 'type' => 'color', 'label' => 'Button Primary BG Color'],
            ['group' => 'theme', 'key' => 'theme_btn_primary_text', 'value' => '#ffffff', 'type' => 'color', 'label' => 'Button Primary Text Color'],
            ['group' => 'theme', 'key' => 'theme_btn_radius',       'value' => '0.75rem', 'type' => 'text', 'label' => 'Button Border Radius (e.g. 0.75rem)'],
            ['group' => 'theme', 'key' => 'theme_btn_secondary_border', 'value' => '#1E4A2D', 'type' => 'color', 'label' => 'Button Secondary Border Color'],
            ['group' => 'theme', 'key' => 'theme_card_radius',         'value' => '0rem', 'type' => 'text', 'label' => 'Card Radius'],
            ['group' => 'theme', 'key' => 'theme_panel_radius',        'value' => '2rem', 'type' => 'text', 'label' => 'Panel Radius'],
            ['group' => 'theme', 'key' => 'theme_card_border_color',   'value' => '#DBDEDA', 'type' => 'color', 'label' => 'Card Border Color'],
            ['group' => 'theme', 'key' => 'theme_card_hover_border_color', 'value' => '#A47148', 'type' => 'color', 'label' => 'Card Hover Border Color'],
            ['group' => 'theme', 'key' => 'theme_form_input_bg',       'value' => '#F8F8F6', 'type' => 'color', 'label' => 'Form Input Background'],
            ['group' => 'theme', 'key' => 'theme_form_input_border',   'value' => '#DADDD8', 'type' => 'color', 'label' => 'Form Input Border'],
            ['group' => 'theme', 'key' => 'theme_form_focus_color',    'value' => '#A47148', 'type' => 'color', 'label' => 'Form Focus Accent'],
            ['group' => 'theme', 'key' => 'theme_section_padding',     'value' => 'clamp(4.5rem, 8vw, 10rem)', 'type' => 'text', 'label' => 'Section Vertical Padding'],
            ['group' => 'theme', 'key' => 'theme_motion_preset',       'value' => 'refined', 'type' => 'text', 'label' => 'Motion Preset'],
        ]);

        // Navigation
        $settings = array_merge($settings, [
            ['group' => 'navigation', 'key' => 'nav_items_json',         'value' => '[]', 'type' => 'json', 'label' => 'Navigation Items (JSON: leave [] to use auto-generated from service categories)'],
            ['group' => 'navigation', 'key' => 'nav_cta_text',           'value' => 'Book a Consultation', 'type' => 'text', 'label' => 'Nav CTA Button Text'],
            ['group' => 'navigation', 'key' => 'nav_cta_url',            'value' => '/contact', 'type' => 'text', 'label' => 'Nav CTA Button URL'],
            ['group' => 'navigation', 'key' => 'nav_show_phone',         'value' => '1', 'type' => 'boolean', 'label' => 'Show Phone Number in Top Bar'],
            ['group' => 'navigation', 'key' => 'nav_show_google_rating', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Google Rating in Top Bar'],
            ['group' => 'navigation', 'key' => 'nav_max_services',       'value' => '5', 'type' => 'number', 'label' => 'Max Services Per Category in Mega Menu'],
        ]);

        // Footer
        $settings = array_merge($settings, [
            ['group' => 'footer', 'key' => 'footer_logo_media_id',      'value' => '', 'type' => 'media', 'label' => 'Footer Logo (leave blank to use text)'],
            ['group' => 'footer', 'key' => 'footer_tagline',            'value' => 'Premium landscape design and build serving Ontario, Canada.', 'type' => 'textarea', 'label' => 'Footer Tagline'],
            ['group' => 'footer', 'key' => 'footer_columns_json',       'value' => '[]', 'type' => 'json', 'label' => 'Footer Columns (JSON: [] uses auto-generated Services & Locations columns)'],
            ['group' => 'footer', 'key' => 'footer_bottom_links_json',  'value' => '[{"label":"Privacy Policy","url":"/privacy-policy"},{"label":"Terms \u0026 Conditions","url":"/terms"},{"label":"Sitemap","url":"/sitemap.xml"}]', 'type' => 'json', 'label' => 'Footer Bottom Links (JSON array of {label, url})'],
            ['group' => 'footer', 'key' => 'footer_copyright_text',     'value' => '© {year} Lush Landscape Service. All rights reserved. Licensed & Insured. Serving Ontario, Canada.', 'type' => 'text', 'label' => 'Copyright Text (use {year} for current year)'],
            ['group' => 'footer', 'key' => 'footer_newsletter_enabled', 'value' => '1', 'type' => 'boolean', 'label' => 'Show Newsletter Signup in Footer'],
            ['group' => 'footer', 'key' => 'footer_newsletter_heading', 'value' => 'Landscape Insights & Project Planning', 'type' => 'text', 'label' => 'Newsletter Section Heading'],
            ['group' => 'footer', 'key' => 'footer_newsletter_subtext', 'value' => 'Join 2,000+ Ontario homeowners getting our free monthly newsletter.', 'type' => 'text', 'label' => 'Newsletter Section Subtext'],
        ]);

        // Search & Features
        $settings = array_merge($settings, [
            ['group' => 'features', 'key' => 'search_enabled',           'value' => '1', 'type' => 'boolean', 'label' => 'Enable Site Search'],
            ['group' => 'features', 'key' => 'search_placeholder',       'value' => 'Search services, cities, blog…', 'type' => 'text', 'label' => 'Search Placeholder Text'],
            ['group' => 'features', 'key' => 'search_show_in_header',    'value' => '1', 'type' => 'boolean', 'label' => 'Show Search Icon in Navigation'],
            ['group' => 'features', 'key' => 'search_min_chars',         'value' => '2', 'type' => 'text', 'label' => 'Minimum Characters to Trigger Search'],
            ['group' => 'features', 'key' => 'search_log_queries',       'value' => '1', 'type' => 'boolean', 'label' => 'Log Search Queries for Analytics'],
            ['group' => 'features', 'key' => 'portfolio_detail_enabled', 'value' => '0', 'type' => 'boolean', 'label' => 'Enable Portfolio Detail Pages'],
        ]);

        // Notifications
        $settings = array_merge($settings, [
            ['group' => 'notifications', 'key' => 'announcement_bar_enabled',  'value' => '0', 'type' => 'boolean', 'label' => 'Show Announcement Bar'],
            ['group' => 'notifications', 'key' => 'announcement_bar_text',     'value' => 'Spring 2026 booking now open. Limited project slots available.', 'type' => 'text', 'label' => 'Announcement Bar Message'],
            ['group' => 'notifications', 'key' => 'announcement_bar_url',      'value' => '/contact', 'type' => 'text', 'label' => 'Announcement Bar Link URL (leave blank for no link)'],
            ['group' => 'notifications', 'key' => 'announcement_bar_bg_color', 'value' => '#1E4A2D', 'type' => 'color', 'label' => 'Announcement Bar Background Color'],
            ['group' => 'notifications', 'key' => 'cookie_consent_enabled',    'value' => '1', 'type' => 'boolean', 'label' => 'Show Cookie Consent Banner'],
            ['group' => 'notifications', 'key' => 'cookie_consent_text',       'value' => 'We use cookies to enhance your experience and analyse site traffic. By clicking "Accept", you consent to our use of cookies.', 'type' => 'textarea', 'label' => 'Cookie Consent Banner Text'],
            ['group' => 'notifications', 'key' => 'cookie_consent_link_url',   'value' => '/privacy-policy', 'type' => 'text', 'label' => 'Cookie Policy Link URL'],
        ]);

        // AI Content
        $settings = array_merge($settings, [
            ['group' => 'ai', 'key' => 'openai_api_key',       'value' => '', 'type' => 'text', 'label' => 'OpenAI API Key'],
            ['group' => 'ai', 'key' => 'openai_model',         'value' => 'gpt-4o', 'type' => 'text', 'label' => 'OpenAI Model'],
            ['group' => 'ai', 'key' => 'openai_temperature',   'value' => '0.7', 'type' => 'text', 'label' => 'Temperature (0.0 - 1.0)'],
            ['group' => 'ai', 'key' => 'ai_features_enabled',  'value' => '0', 'type' => 'boolean', 'label' => 'Enable AI Content Features'],
            ['group' => 'ai', 'key' => 'ai_context_markdown',  'value' => '', 'type' => 'textarea', 'label' => 'AI Context Document (Markdown)'],
        ]);

        foreach ($settings as $i => $s) {
            $s['sort_order'] = $i;
            $s['is_public'] = $s['is_public'] ?? false;
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
