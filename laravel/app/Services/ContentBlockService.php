<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ContentBlockService
{
    /**
     * Get all enabled blocks for a given page, ordered by sort_order.
     */
    public static function getBlocks(string $pageType, int $pageId, ?string $sectionKey = null): Collection
    {
        LegacyGovernanceService::legacyRead('content_block_service', $pageType, $pageId);

        return self::getAllBlocks($pageType, $pageId)
            ->filter(fn (object $block) => $block->is_enabled)
            ->when($sectionKey !== null, fn (Collection $blocks) => $blocks->filter(fn (object $block) => $block->section_key === $sectionKey))
            ->values();
    }

    /**
     * Get all blocks (enabled + disabled) for admin editing.
     */
    public static function getAllBlocks(string $pageType, int $pageId): Collection
    {
        LegacyGovernanceService::legacyRead('content_block_service', $pageType, $pageId);

        return BlockBuilderService::getUnifiedBlocks($pageType, $pageId === 0 ? null : $pageId)
            ->map(fn (array $block) => self::toLegacyRecord($pageType, $pageId, $block));
    }

    /**
     * Save blocks from the admin block editor JSON blob.
     * Expects an array of block objects: [{id?, block_type, sort_order, is_enabled, content, section_key?}]
     * Deletes blocks not present in the incoming list.
     */
    public static function saveBlocks(string $pageType, int $pageId, array $blocksData): void
    {
        LegacyGovernanceService::denyLegacyWrite('content_block_service.saveBlocks', [
            'page_type' => $pageType,
            'page_id' => $pageId,
        ]);

        $normalizedBlocks = collect($blocksData)
            ->values()
            ->map(function (array $block, int $index) {
                $content = is_array($block['content'] ?? null) ? $block['content'] : [];
                if (! empty($block['section_key'])) {
                    $content['_legacy_section_key'] = $block['section_key'];
                }

                return [
                    'id' => $block['id'] ?? null,
                    'block_type' => $block['block_type'],
                    'category' => $block['category'] ?? (BlockBuilderService::typeConfig($block['block_type'])['category'] ?? 'content'),
                    'is_enabled' => (bool) ($block['is_enabled'] ?? true),
                    'show_on_desktop' => (bool) ($block['show_on_desktop'] ?? true),
                    'show_on_tablet' => (bool) ($block['show_on_tablet'] ?? true),
                    'show_on_mobile' => (bool) ($block['show_on_mobile'] ?? true),
                    'visible_from' => $block['visible_from'] ?? null,
                    'visible_until' => $block['visible_until'] ?? null,
                    'content' => $content,
                    'data_source' => is_array($block['data_source'] ?? null) ? $block['data_source'] : null,
                    'styles' => is_array($block['styles'] ?? null) ? $block['styles'] : null,
                    'custom_id' => $block['custom_id'] ?? null,
                    'attributes' => is_array($block['attributes'] ?? null) ? $block['attributes'] : null,
                    'animation' => $block['animation'] ?? null,
                    'sort_order' => (int) ($block['sort_order'] ?? ($index + 1)),
                ];
            })
            ->sortBy('sort_order')
            ->values()
            ->map(fn (array $block) => collect($block)->except('sort_order')->all())
            ->all();

        BlockBuilderService::saveUnifiedBlocks($pageType, $pageId === 0 ? null : $pageId, $normalizedBlocks);
    }

    /**
     * Return the config for a given block type.
     */
    public static function typeConfig(string $blockType): array
    {
        return config('content_blocks.types.'.$blockType, []);
    }

    /**
     * Return all registered block types including fields for the Alpine editor.
     */
    public static function allTypes(): array
    {
        return BlockBuilderService::allTypes();
    }

    private static function toLegacyRecord(string $pageType, int $pageId, array $block): object
    {
        $content = is_array($block['content'] ?? null) ? $block['content'] : [];

        return (object) [
            'id' => $block['id'] ?? null,
            'page_type' => $pageType,
            'page_id' => $pageId,
            'section_key' => $content['_legacy_section_key'] ?? null,
            'block_type' => $block['block_type'],
            'sort_order' => (int) ($block['sort_order'] ?? 0),
            'is_enabled' => (bool) ($block['is_enabled'] ?? true),
            'content' => $content,
        ];
    }
}
