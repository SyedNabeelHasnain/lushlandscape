<?php

namespace Tests\Feature;

use App\Services\BlockCapabilityAuditService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BlockCapabilityAuditTest extends TestCase
{
    public function test_audit_flags_known_render_and_style_gaps(): void
    {
        $audit = app(BlockCapabilityAuditService::class)->audit();
        $blocks = collect($audit['blocks'])->keyBy('key');

        $this->assertSame(['cta_section', 'faq_directory'], $audit['blocks_missing_render_surface']);
        $this->assertNotContains('navigation_menu', $audit['blocks_missing_render_surface']);
        $this->assertTrue($blocks['site_logo']['has_render_surface']);
        $this->assertTrue($blocks['theme_meta_data']['has_render_surface']);
        $this->assertSame('partial_view', $blocks['navigation_menu']['render_surface']['kind']);

        $this->assertNotContains('bg_image_id', $audit['style_keys_not_rendered']);
        $this->assertNotContains('bg_overlay', $audit['style_keys_not_rendered']);
        $this->assertNotContains('bg_overlay_opacity', $audit['style_keys_not_rendered']);

        $this->assertSame([], $audit['legacy_renderer_views']);
    }

    public function test_audit_command_can_write_markdown_report(): void
    {
        $relativeReportPath = 'storage/framework/testing/block-capability-matrix.md';

        $exitCode = Artisan::call('blocks:audit-capabilities', [
            '--write-report' => $relativeReportPath,
        ]);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Block capability audit summary:', Artisan::output());
        $this->assertTrue(File::exists(base_path($relativeReportPath)));
        $this->assertStringContainsString('Block Capability Matrix', File::get(base_path($relativeReportPath)));
    }
}
