<?php

use App\Models\ContentBlock;
use App\Models\PageBlock;
use App\Models\PageSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrate existing page_sections and page_content_blocks data
     * into the unified page_blocks table.
     */
    public function up(): void
    {
        // Migrate page_sections → page_blocks (as data blocks)
        $sectionToBlockMap = [
            'hero' => 'hero',
            'stats_bar' => 'stats_bar',
            'services_grid' => 'services_grid',
            'local_about' => 'local_about',
            'process_steps' => 'process_steps',
            'portfolio_gallery' => 'portfolio_gallery',
            'testimonials' => 'testimonials',
            'faq_section' => 'faq_section',
            'trust_badges' => 'trust_badges',
            'cta_section' => 'cta_section',
            'service_hero' => 'hero',
            'service_body' => 'rich_text',
            'city_availability' => 'city_availability',
            'local_intro' => 'rich_text',
            'benefits_grid' => 'stats_bar',
            'portfolio_preview' => 'portfolio_gallery',
            'city_grid' => 'city_grid',
            'blog_strip' => 'blog_strip',
        ];

        PageSection::chunk(100, function ($sections) use ($sectionToBlockMap) {
            foreach ($sections as $section) {
                $blockType = $sectionToBlockMap[$section->section_key] ?? 'rich_text';
                $category = in_array($blockType, ['hero', 'services_grid', 'testimonials', 'portfolio_gallery', 'faq_section', 'city_grid', 'blog_strip', 'stats_bar', 'process_steps', 'trust_badges', 'cta_section', 'local_about', 'city_availability'])
                    ? 'data'
                    : 'content';

                PageBlock::create([
                    'page_type' => $section->page_type,
                    'page_id' => $section->page_id,
                    'block_type' => $blockType,
                    'category' => $category,
                    'parent_id' => null,
                    'sort_order' => $section->sort_order,
                    'is_enabled' => $section->is_enabled,
                    'show_on_desktop' => $section->show_on_desktop,
                    'show_on_tablet' => $section->show_on_mobile, // tablet inherits from mobile
                    'show_on_mobile' => $section->show_on_mobile,
                    'content' => $section->settings,
                    'data_source' => $this->getDataSourceForBlock($blockType, $section->page_type),
                    'styles' => null,
                ]);
            }
        });

        // Migrate page_content_blocks → page_blocks (as content blocks)
        ContentBlock::chunk(100, function ($blocks) {
            foreach ($blocks as $block) {
                PageBlock::create([
                    'page_type' => $block->page_type,
                    'page_id' => $block->page_id,
                    'block_type' => $block->block_type,
                    'category' => 'content',
                    'parent_id' => null,
                    'sort_order' => $block->sort_order,
                    'is_enabled' => $block->is_enabled,
                    'show_on_desktop' => true,
                    'show_on_tablet' => true,
                    'show_on_mobile' => true,
                    'content' => $block->content,
                    'data_source' => null,
                    'styles' => null,
                ]);
            }
        });
    }

    public function down(): void
    {
        // Safety: only remove blocks that were created by this migration
        // (blocks migrated from page_sections and page_content_blocks)
        // Since we can't distinguish them after migration, require explicit confirmation
        if (! app()->environment('local', 'testing')) {
            throw new \RuntimeException('This migration cannot be rolled back in production. It would delete all page blocks.');
        }

        PageBlock::whereNotNull('id')->delete();
    }

    /**
     * Get the data_source config for a block type based on page type.
     */
    private function getDataSourceForBlock(string $blockType, string $pageType): ?array
    {
        $blockConfig = config('blocks.types.'.$blockType, []);

        return $blockConfig['data_source'] ?? null;
    }
};
