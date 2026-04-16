<?php

namespace Tests\Feature;

use Tests\TestCase;

class Phase2RegistryTest extends TestCase
{
    public function test_premium_critical_blocks_have_governance_required_fields(): void
    {
        $this->assertSame(['heading'], config('blocks.types.hero.governance.required_fields'));
        $this->assertSame(['heading'], config('blocks.types.section_header.governance.required_fields'));
        $this->assertSame(['cards'], config('blocks.types.cards_grid.governance.required_fields'));
        $this->assertSame(['title', 'button_text', 'button_url'], config('blocks.types.cta_section.governance.required_fields'));
        $this->assertSame(['form_slug'], config('blocks.types.form_block.governance.required_fields'));
        $this->assertSame(['template_id'], config('blocks.types.dynamic_loop.governance.required_fields'));
    }

    public function test_cta_section_defines_variants_for_editor_field_visibility(): void
    {
        $variants = config('blocks.types.cta_section.governance.variants');
        $this->assertIsArray($variants);
        $this->assertArrayHasKey('panel', $variants);
        $this->assertArrayHasKey('split', $variants);
        $this->assertArrayHasKey('inline', $variants);
        $this->assertIsArray($variants['panel']['visible_fields']);
    }

    public function test_template_card_shell_is_restricted_to_template_card_pages(): void
    {
        $this->assertSame(['template_card'], config('blocks.types.template_card_shell.governance.allowed_page_types'));
    }
}
