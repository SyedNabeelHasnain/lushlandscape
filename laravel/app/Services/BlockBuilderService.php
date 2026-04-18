<?php

namespace App\Services;

use App\Models\PageBlock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class BlockBuilderService
{

    /**
     * Get all enabled blocks for a page, ordered by sort_order.
     */
    public static function getBlocks(string $pageType, mixed $pageId, ?string $device = null): Collection
    {
        $pageId = self::normalizePageId($pageType, $pageId);
        $cacheKey = "unified_page_blocks_{$pageType}_{$pageId}_{$device}";

        return Cache::remember($cacheKey, 300, function () use ($pageType, $pageId, $device) {
            $blocksQuery = PageBlock::forPage($pageType, $pageId)
                ->enabled()
                ->visibleNow()
                ->orderBy('sort_order');

            if ($device) {
                $blocksQuery->forDevice($device);
            }

            return self::nestBlockCollection($blocksQuery->get());
        });
    }

    /**
     * Get all blocks for admin editing.
     */
    public static function getUnifiedBlocks(string $pageType, ?int $pageId): Collection
    {
        $pageId = self::normalizePageId($pageType, $pageId);

        return self::nestBlockCollection(
            PageBlock::forPage($pageType, $pageId)
                ->orderBy('sort_order')
                ->get()
        )
            ->map(fn ($block) => self::mapEditorBlock($block))
            ->values();
    }

    /**
     * Save blocks from the unified admin builder.
     */
    public static function saveUnifiedBlocks(string $pageType, mixed $pageId, array $blocksData): void
    {
        $pageId = self::normalizePageId($pageType, $pageId);

        BlockGovernanceService::validateBlocksForPage($pageType, $blocksData);

        $incomingBlockIds = [];

        self::persistUnifiedBlocks($pageType, $pageId, $blocksData, null, $incomingBlockIds);

        self::deleteRemovedBlocks($pageType, $pageId, $incomingBlockIds);

        self::clearCache($pageType, $pageId);
    }

    /**
     * Get all blocks (enabled + disabled) for admin editing.
     */
    public static function getAllBlocks(string $pageType, ?int $pageId): Collection
    {
        $pageId = self::normalizePageId($pageType, $pageId);

        return self::nestBlockCollection(
            PageBlock::forPage($pageType, $pageId)
                ->orderBy('sort_order')
                ->get()
        );
    }

    /**
     * Save blocks from the admin block editor.
     * Handles create, update, delete, and reordering.
     */
    public static function saveBlocks(string $pageType, mixed $pageId, array $blocksData): void
    {
        $pageId = self::normalizePageId($pageType, $pageId);

        BlockGovernanceService::validateBlocksForPage($pageType, $blocksData);

        $incomingIds = [];

        foreach ($blocksData as $index => $block) {
            $attrs = [
                'page_type' => $pageType,
                'page_id' => $pageId,
                'block_type' => $block['block_type'],
                'category' => $block['category'] ?? self::getCategoryForType($block['block_type']),
                'parent_id' => $block['parent_id'] ?? null,
                'sort_order' => $index + 1,
                'is_enabled' => (bool) ($block['is_enabled'] ?? true),
                'show_on_desktop' => (bool) ($block['show_on_desktop'] ?? true),
                'show_on_tablet' => (bool) ($block['show_on_tablet'] ?? true),
                'show_on_mobile' => (bool) ($block['show_on_mobile'] ?? true),
                'visible_from' => $block['visible_from'] ?? null,
                'visible_until' => $block['visible_until'] ?? null,
                'content' => is_array($block['content'] ?? null) ? $block['content'] : [],
                'data_source' => is_array($block['data_source'] ?? null) ? $block['data_source'] : null,
                'styles' => is_array($block['styles'] ?? null) ? $block['styles'] : null,
                'custom_id' => $block['custom_id'] ?? null,
                'attributes' => is_array($block['attributes'] ?? null) ? $block['attributes'] : null,
                'animation' => $block['animation'] ?? null,
            ];

            if (! empty($block['id'])) {
                PageBlock::where('id', $block['id'])
                    ->forPage($pageType, $pageId)
                    ->update($attrs);

                $incomingIds[] = (int) $block['id'];
            } else {
                $incomingIds[] = PageBlock::create($attrs)->id;
            }
        }

        self::deleteRemovedBlocks($pageType, $pageId, $incomingIds);

        self::clearCache($pageType, $pageId);
    }

    private static function mapEditorBlock(PageBlock $block): array
    {
        return [
            'id' => $block->id,
            'block_type' => $block->block_type,
            'category' => $block->category,
            'is_enabled' => (bool) $block->is_enabled,
            'show_on_desktop' => (bool) $block->show_on_desktop,
            'show_on_tablet' => (bool) $block->show_on_tablet,
            'show_on_mobile' => (bool) $block->show_on_mobile,
            'sort_order' => (int) $block->sort_order,
            'visible_from' => $block->visible_from?->toIso8601String(),
            'visible_until' => $block->visible_until?->toIso8601String(),
            'content' => $block->content ?? [],
            'data_source' => $block->data_source ?? null,
            'styles' => $block->styles ?? self::styleDefaults(),
            'custom_id' => $block->custom_id,
            'attributes' => $block->attributes ?? null,
            'animation' => $block->animation,
            'children' => $block->children->map(fn (PageBlock $child) => self::mapEditorBlock($child))->values()->all(),
            '_uid' => 'block_'.($block->id ?: uniqid()),
            '_open' => false,
        ];
    }

    /**
     * Resolve the data for a data-driven block.
     * Returns the query results based on the block's data_source config.
     */
    public static function resolveBlockData(PageBlock $block, array $context = []): Collection
    {
        $dataSource = $block->getFinalDataSource();

        if (empty($dataSource) || empty($dataSource['model'])) {
            return new Collection;
        }

        $modelClass = $dataSource['model'];

        if ($modelClass === 'auto' && ! empty($block->content['data_model'])) {
            $modelClass = $block->content['data_model'];
        }

        if (! class_exists($modelClass)) {
            return new Collection;
        }

        $query = $modelClass::query();

        if (method_exists($modelClass, 'scopePublished')) {
            $query->published();
        }

        if (! empty($dataSource['scope']) && method_exists($modelClass, 'scope'.ucfirst($dataSource['scope']))) {
            $query->{$dataSource['scope']}();
        }

        $filters = $dataSource['filters'] ?? [];
        foreach ($filters as $field => $value) {
            if ($value === 'auto') {
                $value = $context[$field] ?? null;

                if ($field === 'parent_id' && empty($value) && ! empty($context['category_id'])) {
                    $value = $context['category_id'];
                }
            }

            if ($value !== null && $value !== 'all') {
                $query->where($field, $value);
            } elseif ($value === null && array_key_exists($field, $filters) && $filters[$field] === null) {
                $query->whereNull($field);
            }
        }

        if (! empty($dataSource['manual_ids'])) {
            $ids = is_array($dataSource['manual_ids']) ? $dataSource['manual_ids'] : explode(',', $dataSource['manual_ids']);
            $ids = array_filter(array_map('intval', $ids));
            if (! empty($ids)) {
                $query->whereIn('id', $ids);
            }
        }

        if (! empty($dataSource['with'])) {
            $with = $dataSource['with'];
            if (is_array($with)) {
                $simpleWith = array_filter($with, fn ($value) => is_string($value));
                if (! empty($simpleWith)) {
                    $query->with($simpleWith);
                }

                $complexWith = array_filter($with, fn ($value) => is_array($value), ARRAY_FILTER_USE_BOTH);
                foreach ($complexWith as $relation => $constraints) {
                    if (is_string($relation) && is_array($constraints)) {
                        $query->with([
                            $relation => function ($relationQuery) use ($constraints) {
                                if (! empty($constraints['where'])) {
                                    foreach ($constraints['where'] as $field => $value) {
                                        $relationQuery->where($field, $value);
                                    }
                                }
                                if (! empty($constraints['orderBy'])) {
                                    $relationQuery->orderBy($constraints['orderBy'], $constraints['orderDir'] ?? 'asc');
                                }
                                if (! empty($constraints['limit'])) {
                                    $relationQuery->limit((int) $constraints['limit']);
                                }
                            },
                        ]);
                    } elseif (is_string($constraints)) {
                        $query->with($constraints);
                    }
                }
            }
        }

        $query->orderBy($dataSource['order_by'] ?? 'sort_order', $dataSource['order_dir'] ?? 'asc');

        $limit = $dataSource['limit'] ?? null;
        if ($limit === 'auto' && ! empty($block->content['limit'])) {
            $limit = $block->content['limit'];
        }
        if (! empty($limit) && is_numeric($limit)) {
            $query->limit((int) $limit);
        }

        return $query->get();
    }

    /**
     * Get the config for a block type.
     */
    public static function typeConfig(string $blockType): array
    {
        $configKey = Config::get('blocks.section_map.'.$blockType, $blockType);

        return Config::get('blocks.types.'.$configKey, []);
    }

    /**
     * Get all registered block types grouped by category.
     */
    public static function allTypes(): array
    {
        return (new Collection(Config::get('blocks.types', [])))
            ->map(function ($cfg, $key) {
                $governance = is_array($cfg['governance'] ?? null) ? $cfg['governance'] : [];

                $governance = array_merge([
                    'allowed_page_types' => null,
                    'variants' => null,
                    'required_fields' => [],
                    'supports_children_rules' => null,
                    'media_rules' => null,
                    'motion_rules' => null,
                    'fallback_behavior' => null,
                ], $governance);

                return [
                    'key' => $key,
                    'label' => $cfg['label'],
                    'icon' => $cfg['icon'],
                    'category' => $cfg['category'] ?? 'content',
                    'content_fields' => $cfg['content_fields'] ?? [],
                    'data_source' => $cfg['data_source'] ?? null,
                    'supports_children' => $cfg['supports_children'] ?? false,
                    'defaults' => $cfg['defaults'] ?? [],
                    'governance' => $governance,
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Get block types for a specific category.
     */
    public static function typesByCategory(string $category): array
    {
        return (new Collection(Config::get('blocks.types', [])))
            ->filter(fn ($cfg) => ($cfg['category'] ?? 'content') === $category)
            ->map(fn ($cfg, $key) => [
                'key' => $key,
                'label' => $cfg['label'],
                'icon' => $cfg['icon'],
                'defaults' => $cfg['defaults'] ?? [],
            ])
            ->values()
            ->all();
    }

    /**
     * Get style field definitions.
     */
    public static function styleFields(): array
    {
        return Config::get('blocks.style_fields', []);
    }

    /**
     * Get style defaults.
     */
    public static function styleDefaults(): array
    {
        return Config::get('blocks.style_defaults', []);
    }

    /**
     * Clear the block cache for a page.
     */
    public static function clearCache(string $pageType, mixed $pageId): void
    {
        $pageId = self::normalizePageId($pageType, $pageId);
        foreach (['all', 'desktop', 'tablet', 'mobile', ''] as $device) {
            Cache::forget("unified_page_blocks_{$pageType}_{$pageId}_{$device}");
            Cache::forget("unified_page_blocks_{$pageType}_{$pageId}_");
        }
    }

    /**
     * Determine the category for a block type.
     */
    private static function getCategoryForType(string $blockType): string
    {
        return self::typeConfig($blockType)['category'] ?? 'content';
    }

    /**
     * Parse dynamic variables in a string based on the provided context.
     * Replaces {variables} with corresponding values from the current block variable registry.
     */
    public static function parseDynamicString(string $subject, array $context): string
    {
        return app(BlockVariableService::class)->parseString($subject, $context);
    }

    /**
     * Parse all string fields recursively in an array to replace dynamic variables.
     */
    public static function parseDynamicContent(array $content, array $context): array
    {
        return app(BlockVariableService::class)->parseContent($content, $context);
    }

    private static function normalizePageId(string $pageType, mixed $pageId): mixed
    {
        return ($pageId == 0 || $pageId === '0' || $pageType === 'home') ? null : $pageId;
    }

    private static function deleteRemovedTopLevelBlocks(string $pageType, mixed $pageId, array $incomingTopLevelIds): void
    {
        $staleQuery = PageBlock::forPage($pageType, $pageId)->topLevel();
        if (! empty($incomingTopLevelIds)) {
            $staleQuery->whereNotIn('id', $incomingTopLevelIds);
        }

        $idsToDelete = $staleQuery->pluck('id')->all();
        if ($idsToDelete === []) {
            return;
        }

        $pendingParentIds = $idsToDelete;
        while ($pendingParentIds !== []) {
            $childIds = PageBlock::forPage($pageType, $pageId)
                ->whereIn('parent_id', $pendingParentIds)
                ->pluck('id')
                ->all();

            $childIds = array_values(array_diff($childIds, $idsToDelete));
            if ($childIds === []) {
                break;
            }

            $idsToDelete = array_merge($idsToDelete, $childIds);
            $pendingParentIds = $childIds;
        }

        PageBlock::whereIn('id', $idsToDelete)->delete();
    }

    private static function deleteRemovedBlocks(string $pageType, mixed $pageId, array $incomingIds): void
    {
        $staleQuery = PageBlock::forPage($pageType, $pageId);

        if ($incomingIds !== []) {
            $staleQuery->whereNotIn('id', $incomingIds);
        }

        $staleQuery->delete();
    }

    private static function persistUnifiedBlocks(string $pageType, mixed $pageId, array $blocksData, ?int $parentId, array &$incomingIds): void
    {
        foreach ($blocksData as $index => $item) {
            if (isset($item['data_source_id']) && !isset($item['data_source'])) {
                if (in_array($item['block_type'] ?? '', ['consultation_form_split', 'consultation_wizard_luxury', 'contact_form_luxury'])) {
                    $item['data_source'] = [
                        'model' => 'App\Models\Form',
                        'filters' => ['slug' => $item['data_source_id']]
                    ];
                }
            }

            $attrs = [
                'page_type' => $pageType,
                'page_id' => $pageId,
                'block_type' => $item['block_type'],
                'category' => $item['category'] ?? self::getCategoryForType($item['block_type']),
                'parent_id' => $parentId,
                'sort_order' => $index + 1,
                'is_enabled' => (bool) ($item['is_enabled'] ?? true),
                'show_on_desktop' => (bool) ($item['show_on_desktop'] ?? true),
                'show_on_tablet' => (bool) ($item['show_on_tablet'] ?? true),
                'show_on_mobile' => (bool) ($item['show_on_mobile'] ?? true),
                'visible_from' => $item['visible_from'] ?? null,
                'visible_until' => $item['visible_until'] ?? null,
                'content' => is_array($item['content'] ?? null) ? $item['content'] : [],
                'data_source' => is_array($item['data_source'] ?? null) ? $item['data_source'] : null,
                'styles' => is_array($item['styles'] ?? null) ? $item['styles'] : null,
                'custom_id' => $item['custom_id'] ?? null,
                'attributes' => is_array($item['attributes'] ?? null) ? $item['attributes'] : null,
                'animation' => $item['animation'] ?? null,
            ];

            if (! empty($item['id']) && is_numeric($item['id'])) {
                PageBlock::where('id', $item['id'])
                    ->forPage($pageType, $pageId)
                    ->update($attrs);

                $blockId = (int) $item['id'];
            } else {
                $blockId = PageBlock::create($attrs)->id;
            }

            $incomingIds[] = $blockId;

            self::persistUnifiedBlocks(
                $pageType,
                $pageId,
                is_array($item['children'] ?? null) ? $item['children'] : [],
                $blockId,
                $incomingIds
            );
        }
    }

    private static function nestBlockCollection(Collection $blocks): Collection
    {
        $grouped = $blocks
            ->sortBy('sort_order')
            ->groupBy(fn (PageBlock $block) => $block->parent_id ?? 'root');

        $build = function ($parentId = null) use (&$build, $grouped): Collection {
            $children = $grouped->get($parentId ?? 'root', collect());

            return $children
                ->sortBy('sort_order')
                ->values()
                ->map(function (PageBlock $block) use (&$build) {
                    $block->setRelation('children', $build($block->id));

                    return $block;
                });
        };

        return $build();
    }

}
