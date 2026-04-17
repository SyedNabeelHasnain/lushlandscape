<?php

namespace Tests\Feature;

use App\Services\BlockBuilderService;
use Tests\TestCase;

class BlockBuilderServiceRegistryTest extends TestCase
{
    public function test_canonical_page_type_normalizes_legacy_taxonomy_aliases(): void
    {
        $this->assertSame('blog_category', BlockBuilderService::canonicalPageType('blog-categories'));
        $this->assertSame('portfolio_category', BlockBuilderService::canonicalPageType('portfolio-categories'));
        $this->assertSame('faq_category', BlockBuilderService::canonicalPageType('faq-categories'));
        $this->assertSame('review_category', BlockBuilderService::canonicalPageType('review-categories'));
        $this->assertSame('service', BlockBuilderService::canonicalPageType('service'));
    }

    public function test_exportable_page_types_follow_supported_builder_surfaces(): void
    {
        $exportableTypes = BlockBuilderService::exportablePageTypes();

        $this->assertContains('theme_layout', $exportableTypes);
        $this->assertContains('template_card', $exportableTypes);
        $this->assertContains('blog_category', $exportableTypes);
        $this->assertContains('portfolio_category', $exportableTypes);
        $this->assertNotContains('faq_category', $exportableTypes);
        $this->assertNotContains('review_category', $exportableTypes);

        $this->assertTrue(BlockBuilderService::supportsBlockExport('theme_layout'));
        $this->assertTrue(BlockBuilderService::supportsBlockExport('template_card'));
        $this->assertTrue(BlockBuilderService::supportsBlockExport('blog-categories'));
        $this->assertFalse(BlockBuilderService::supportsBlockExport('faq_category'));
    }

    public function test_registry_includes_new_editorial_and_theme_builder_blocks(): void
    {
        $blockTypes = config('blocks.types', []);

        $this->assertArrayHasKey('cards_grid', $blockTypes);
        $this->assertArrayHasKey('image_text', $blockTypes);
        $this->assertArrayHasKey('feature_list', $blockTypes);
        $this->assertArrayHasKey('editorial_split_feature', $blockTypes);
        $this->assertArrayHasKey('contact_info', $blockTypes);
        $this->assertArrayHasKey('social_links', $blockTypes);
        $this->assertArrayHasKey('newsletter_cta', $blockTypes);
        $this->assertArrayHasKey('button_group', $blockTypes);
        $this->assertArrayHasKey('theme_header_shell', $blockTypes);
        $this->assertArrayHasKey('theme_contact_strip', $blockTypes);
        $this->assertArrayHasKey('theme_cta_group', $blockTypes);
        $this->assertArrayHasKey('theme_social_links', $blockTypes);
        $this->assertArrayHasKey('theme_newsletter_panel', $blockTypes);
        $this->assertArrayHasKey('theme_footer_columns', $blockTypes);
        $this->assertArrayHasKey('theme_legal_bar', $blockTypes);
    }

    public function test_theme_style_defaults_remove_section_spacing_from_theme_blocks(): void
    {
        $themeDefaults = config('blocks.theme_style_defaults.desktop', []);

        $this->assertSame('none', $themeDefaults['spacing_preset']);
        $this->assertSame('none', $themeDefaults['padding_top']);
        $this->assertSame('none', $themeDefaults['padding_bottom']);
        $this->assertSame('none', $themeDefaults['margin_bottom']);
    }

    public function test_blocks_have_theme_style_defaults()
    {
        $config = config('blocks.theme_style_defaults');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('desktop', $config);
        $this->assertArrayHasKey('surface_preset', $config['desktop']);
    }

    public function test_blocks_have_phase_a_presentation_controls()
    {
        $styleFields = config('blocks.style_fields');
        $keys = collect($styleFields)->pluck('key')->toArray();

        $this->assertContains('surface_preset', $keys);
        $this->assertContains('transition_top', $keys);
        $this->assertContains('transition_bottom', $keys);
        $this->assertContains('content_width', $keys);
        $this->assertContains('heading_scale_preset', $keys);
        $this->assertContains('card_skin_preset', $keys);
    }

    public function test_blocks_have_phase_b_premium_families()
    {
        $blockTypes = config('blocks.types', []);

        $this->assertArrayHasKey('marquee_strip', $blockTypes);
        $this->assertArrayHasKey('parallax_media_band', $blockTypes);
        $this->assertArrayHasKey('authority_grid', $blockTypes);
        $this->assertArrayHasKey('service_area_enclave', $blockTypes);
        $this->assertArrayHasKey('split_consultation_panel', $blockTypes);
    }

    public function test_phase_b_premium_controls_exist_in_registry()
    {
        $blockTypes = config('blocks.types', []);
        
        // Marquee Strip
        $marqueeFields = collect($blockTypes['marquee_strip']['content_fields'])->pluck('key')->toArray();
        $this->assertContains('separator_style', $marqueeFields);

        // Parallax Media Band
        $parallaxFields = collect($blockTypes['parallax_media_band']['content_fields'])->pluck('key')->toArray();
        $this->assertContains('parallax_intensity', $parallaxFields);

        // Service Area Enclave
        $enclaveFields = collect($blockTypes['service_area_enclave']['content_fields'])->pluck('key')->toArray();
        $this->assertContains('presentation_mode', $enclaveFields);

        // Services Grid
        $servicesGridFields = collect($blockTypes['services_grid']['content_fields'])->pluck('key')->toArray();
        $this->assertContains('show_icon', $servicesGridFields);
        $this->assertContains('show_usp_list', $servicesGridFields);
        $this->assertContains('card_cta_label', $servicesGridFields);
        
        $servicesGridVariantField = collect($blockTypes['services_grid']['content_fields'])->firstWhere('key', 'variant');
        $this->assertArrayHasKey('premium-2x2', $servicesGridVariantField['options']);
        
        // Portfolio Gallery
        $portfolioLayoutField = collect($blockTypes['portfolio_gallery']['content_fields'])->firstWhere('key', 'layout');
        $this->assertArrayHasKey('rail', $portfolioLayoutField['options']);
        
        // Process Steps
        $processVariantField = collect($blockTypes['process_steps']['content_fields'])->firstWhere('key', 'variant');
        $this->assertArrayHasKey('premium-stack', $processVariantField['options']);
        
        // Section Header
        $sectionHeaderVariantField = collect($blockTypes['section_header']['content_fields'])->firstWhere('key', 'variant');
        $this->assertArrayHasKey('title-only', $sectionHeaderVariantField['options']);
        $this->assertArrayHasKey('with-right-cta', $sectionHeaderVariantField['options']);
        $this->assertArrayHasKey('full-editorial', $sectionHeaderVariantField['options']);
        
        // Split Consultation Panel
        $splitConsultationFields = collect($blockTypes['split_consultation_panel']['content_fields'])->pluck('key')->toArray();
        $this->assertContains('form_slug', $splitConsultationFields);
        
        $splitDefaults = $blockTypes['split_consultation_panel']['defaults'];
        $this->assertStringNotContainsString('quote', strtolower($splitDefaults['trust_lines']));
        $this->assertNotEquals('request-quote', $splitDefaults['form_slug']);
        $this->assertEquals('contact-us', $splitDefaults['form_slug']);
    }
}
