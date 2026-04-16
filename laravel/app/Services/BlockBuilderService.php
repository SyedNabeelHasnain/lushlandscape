<?php

namespace App\Services;

use App\Models\ContentBlock;
use App\Models\PageBlock;
use App\Models\PageSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class BlockBuilderService
{
    private const LEGACY_PAGE_TYPE_ALIASES = [
        'blog-categories' => 'blog_category',
        'portfolio-categories' => 'portfolio_category',
        'faq-categories' => 'faq_category',
        'review-categories' => 'review_category',
    ];

    private const LAYOUT_SECTION_VIEW_MAP = [
        'hero' => ['type' => 'view', 'name' => 'frontend.sections.hero'],
        'service_hero' => ['type' => 'view', 'name' => 'frontend.sections.service-hero'],
        'stats_bar' => ['type' => 'view', 'name' => 'frontend.sections.stats-bar'],
        'services_grid' => ['type' => 'view', 'name' => 'frontend.sections.services-grid'],
        'local_about' => ['type' => 'view', 'name' => 'frontend.sections.local-about'],
        'local_intro' => ['type' => 'view', 'name' => 'frontend.sections.local-intro'],
        'process_steps' => ['type' => 'view', 'name' => 'frontend.sections.process-steps'],
        'portfolio_gallery' => ['type' => 'view', 'name' => 'frontend.sections.portfolio-gallery'],
        'portfolio_preview' => ['type' => 'component', 'name' => 'frontend.portfolio-preview'],
        'testimonials' => ['type' => 'view', 'name' => 'frontend.sections.testimonials'],
        'faq_section' => ['type' => 'view', 'name' => 'frontend.sections.faq-section'],
        'cta_section' => ['type' => 'view', 'name' => 'frontend.sections.cta-section'],
        'trust_badges' => ['type' => 'view', 'name' => 'frontend.sections.trust-badges'],
        'city_availability' => ['type' => 'view', 'name' => 'frontend.sections.city-availability'],
        'service_body' => ['type' => 'view', 'name' => 'frontend.sections.service-body'],
        'city_grid' => ['type' => 'component', 'name' => 'frontend.city-grid'],
        'benefits_grid' => ['type' => 'component', 'name' => 'frontend.benefits-grid'],
        'blog_strip' => ['type' => 'component', 'name' => 'frontend.blog-preview-strip'],
        'scp_hero' => ['type' => 'view', 'name' => 'frontend.sections.scp-hero'],
    ];

    private const BLOCK_EXPORT_BASE_PAGE_TYPES = [
        'static_page',
        'city',
        'service',
        'service_category',
        'service_city_page',
        'home',
        'blog_index',
        'blog_post',
        'portfolio_index',
        'portfolio_project',
        'services_hub',
        'locations_hub',
        'theme_layout',
        'template_card',
    ];

    private static array $legacyBackfillCache = [];

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
            'is_layout_section' => self::isLayoutSection($block->block_type),
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
     * Legacy shim to merge old sections + content blocks into the unified format.
     */
    public static function saveLegacyBlocks(string $pageType, mixed $pageId, array $sectionsData, array $blocksData): void
    {
        LegacyGovernanceService::denyLegacyWrite('block_builder.saveLegacyBlocks', [
            'page_type' => $pageType,
            'page_id' => $pageId,
        ]);

        $unifiedBlocks = [];

        foreach ($sectionsData as $key => $data) {
            $unifiedBlocks[] = [
                'block_type' => $key,
                'category' => self::getCategoryForType($key),
                'is_enabled' => (bool) ($data['is_enabled'] ?? true),
                'show_on_desktop' => (bool) ($data['desktop'] ?? true),
                'show_on_tablet' => (bool) ($data['mobile'] ?? true),
                'show_on_mobile' => (bool) ($data['mobile'] ?? true),
                'content' => is_array($data['settings'] ?? null) ? $data['settings'] : [],
            ];
        }

        self::saveUnifiedBlocks($pageType, $pageId, array_merge($unifiedBlocks, $blocksData));
    }

    /**
     * Remove blocks for a page from both the new and legacy tables.
     */
    public static function deleteAllBlocksForPage(string $pageType, mixed $pageId): void
    {
        $pageId = self::normalizePageId($pageType, $pageId);

        PageBlock::forPage($pageType, $pageId)->delete();
        self::clearCache($pageType, $pageId);
    }

    /**
     * Return the distinct legacy pages that still have content in page_sections
     * or page_content_blocks.
     */
    public static function legacyPageInventory(): Collection
    {
        $pages = collect();

        $legacyContentPages = ContentBlock::query()
            ->select(['page_type', 'page_id'])
            ->distinct()
            ->get()
            ->map(function (ContentBlock $row) {
                $pageType = self::canonicalPageType((string) $row->page_type);
                $pageId = self::normalizePageId($pageType, $row->page_id);

                return ['page_type' => $pageType, 'page_id' => $pageId];
            });

        $legacySectionPages = PageSection::query()
            ->select(['page_type', 'page_id'])
            ->distinct()
            ->get()
            ->map(function (PageSection $row) {
                $pageType = self::canonicalPageType((string) $row->page_type);
                $pageId = self::normalizePageId($pageType, $row->page_id);

                return ['page_type' => $pageType, 'page_id' => $pageId];
            });

        $pages = $pages
            ->merge($legacyContentPages)
            ->merge($legacySectionPages)
            ->unique(fn (array $row) => $row['page_type'].'|'.($row['page_id'] ?? 'null'))
            ->values()
            ->sortBy(fn (array $row) => $row['page_type'].'|'.($row['page_id'] ?? 'null'))
            ->values();

        return $pages;
    }

    public static function ensureLegacyBackfilled(string $pageType, mixed $pageId): int
    {
        $pageId = self::normalizePageId($pageType, $pageId);

        return self::syncMissingLegacyBlocks($pageType, $pageId);
    }

    public static function missingLegacyBlockCount(string $pageType, mixed $pageId): int
    {
        $pageId = self::normalizePageId($pageType, $pageId);

        return self::missingLegacyCandidatePayloads($pageType, $pageId)->count();
    }

    public static function legacyRowCounts(string $pageType, mixed $pageId): array
    {
        $pageType = self::canonicalPageType($pageType);
        $pageId = self::normalizePageId($pageType, $pageId);

        $legacyPageTypes = self::legacyPageTypes($pageType);

        $sectionsQuery = PageSection::whereIn('page_type', $legacyPageTypes);
        if ($pageId === null) {
            $sectionsQuery->whereNull('page_id');
        } else {
            $sectionsQuery->where('page_id', $pageId);
        }

        $contentCount = ContentBlock::whereIn('page_type', $legacyPageTypes)
            ->where('page_id', $pageId ?? 0)
            ->count();

        return [
            'page_sections' => $sectionsQuery->count(),
            'page_content_blocks' => $contentCount,
        ];
    }

    public static function pruneLegacyData(string $pageType, mixed $pageId): array
    {
        $pageType = self::canonicalPageType($pageType);
        $pageId = self::normalizePageId($pageType, $pageId);

        if (self::missingLegacyBlockCount($pageType, $pageId) > 0) {
            return ['page_sections' => 0, 'page_content_blocks' => 0];
        }

        $legacyPageTypes = self::legacyPageTypes($pageType);

        $sectionsQuery = PageSection::whereIn('page_type', $legacyPageTypes);
        if ($pageId === null) {
            $sectionsQuery->whereNull('page_id');
        } else {
            $sectionsQuery->where('page_id', $pageId);
        }

        $deletedSections = (int) $sectionsQuery->delete();

        $deletedContentBlocks = (int) ContentBlock::whereIn('page_type', $legacyPageTypes)
            ->where('page_id', $pageId ?? 0)
            ->delete();

        return [
            'page_sections' => $deletedSections,
            'page_content_blocks' => $deletedContentBlocks,
        ];
    }

    public static function canonicalPageType(string $pageType): string
    {
        return self::LEGACY_PAGE_TYPE_ALIASES[$pageType] ?? $pageType;
    }

    public static function exportablePageTypes(): array
    {
        $taxonomyTypes = collect(config('taxonomies', []))
            ->filter(fn (array $cfg) => (bool) ($cfg['supports_page_builder'] ?? false))
            ->keys()
            ->map(fn (string $key) => str_replace('-', '_', Str::singular($key)))
            ->values()
            ->all();

        return array_values(array_unique([
            ...self::BLOCK_EXPORT_BASE_PAGE_TYPES,
            ...$taxonomyTypes,
        ]));
    }

    public static function supportsBlockExport(string $pageType): bool
    {
        return in_array(self::canonicalPageType($pageType), self::exportablePageTypes(), true);
    }

    public static function layoutSectionViewMap(): array
    {
        return self::LAYOUT_SECTION_VIEW_MAP;
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
                    'is_layout_section' => self::isLayoutSection($key),
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

    private static function normalizeLegacyPage(string $pageType, mixed $pageId): array
    {
        $pageType = self::canonicalPageType($pageType);

        return [$pageType, self::normalizePageId($pageType, $pageId)];
    }

    private static function isLayoutSection(string $blockType): bool
    {
        return array_key_exists($blockType, self::layoutSectionViewMap());
    }

    private static function backfillLegacyBlocks(string $pageType, mixed $pageId): void
    {
        $cacheKey = self::legacyCacheKey($pageType, $pageId);
        if (isset(self::$legacyBackfillCache[$cacheKey])) {
            return;
        }
        self::$legacyBackfillCache[$cacheKey] = true;

        self::syncMissingLegacyBlocks($pageType, $pageId);
    }

    private static function syncMissingLegacyBlocks(string $pageType, mixed $pageId): int
    {
        $missingCandidates = self::missingLegacyCandidatePayloads($pageType, $pageId);
        if ($missingCandidates->isEmpty()) {
            return 0;
        }

        $hasExistingUnifiedBlocks = PageBlock::forPage($pageType, $pageId)->exists();
        $nextSortOrder = $hasExistingUnifiedBlocks
            ? ((int) PageBlock::forPage($pageType, $pageId)->max('sort_order')) + 1
            : null;

        foreach ($missingCandidates as $candidate) {
            if ($nextSortOrder !== null) {
                $candidate['sort_order'] = $nextSortOrder++;
            }

            PageBlock::create($candidate);
        }

        self::clearCache($pageType, $pageId);

        return $missingCandidates->count();
    }

    private static function missingLegacyCandidatePayloads(string $pageType, mixed $pageId): Collection
    {
        $legacyCandidates = self::legacyCandidatePayloads($pageType, $pageId);
        if ($legacyCandidates->isEmpty()) {
            return collect();
        }

        $existingComparableCounts = self::existingComparableFingerprintCounts($pageType, $pageId);

        return $legacyCandidates->filter(function (array $candidate) use (&$existingComparableCounts) {
            $fingerprint = self::comparableBlockFingerprint($candidate);

            if (($existingComparableCounts[$fingerprint] ?? 0) > 0) {
                $existingComparableCounts[$fingerprint]--;

                return false;
            }

            return true;
        })->values();
    }

    private static function legacyCandidatePayloads(string $pageType, mixed $pageId): Collection
    {
        LegacyGovernanceService::legacyRead('legacy_tables', $pageType, $pageId);

        $legacyPageTypes = self::legacyPageTypes($pageType);
        $legacyPageId = $pageId ?? 0;

        $legacySections = PageSection::whereIn('page_type', $legacyPageTypes)
            ->where(function ($query) use ($pageId) {
                if ($pageId === null) {
                    $query->whereNull('page_id');
                } else {
                    $query->where('page_id', $pageId);
                }
            })
            ->orderBy('sort_order')
            ->get()
            ->map(fn (PageSection $section) => self::makeLegacySectionPayload($section, $pageType, $pageId));

        $legacyContentBlocks = ContentBlock::whereIn('page_type', $legacyPageTypes)
            ->where('page_id', $legacyPageId)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (ContentBlock $legacyBlock) => self::makeLegacyContentBlockPayload($legacyBlock, $pageType, $pageId));

        return $legacySections
            ->concat($legacyContentBlocks)
            ->values();
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

    private static function legacyPageTypes(string $pageType): array
    {
        $pageType = self::canonicalPageType($pageType);

        $aliases = array_keys(array_filter(
            self::LEGACY_PAGE_TYPE_ALIASES,
            fn (string $canonicalPageType) => $canonicalPageType === $pageType
        ));

        return array_values(array_unique([$pageType, ...$aliases]));
    }

    private static function legacyWrapperToStyles(array $wrapper): ?array
    {
        if ($wrapper === []) {
            return null;
        }

        return [
            'desktop' => array_filter([
                'bg_color' => $wrapper['bg_color'] ?? null,
                'text_color' => $wrapper['text_color'] ?? null,
                'padding_top' => $wrapper['padding_y'] ?? null,
                'padding_bottom' => $wrapper['padding_y'] ?? null,
                'padding_left' => $wrapper['padding_x'] ?? null,
                'padding_right' => $wrapper['padding_x'] ?? null,
                'margin_top' => $wrapper['margin_top'] ?? null,
                'margin_bottom' => $wrapper['margin_bottom'] ?? null,
                'max_width' => $wrapper['max_width'] ?? null,
                'rounded' => $wrapper['rounded'] ?? null,
            ], fn ($value) => $value !== null),
            'tablet' => [],
            'mobile' => [],
        ];
    }

    private static function legacyCacheKey(string $pageType, mixed $pageId): string
    {
        return $pageType.'|'.($pageId ?? 'null');
    }

    private static function makeLegacySectionPayload(PageSection $section, string $pageType, mixed $pageId): array
    {
        $content = is_array($section->settings) ? $section->settings : [];
        $content['_legacy_page_section_id'] = $section->id;

        return [
            'page_type' => $pageType,
            'page_id' => $pageId,
            'block_type' => $section->section_key,
            'category' => self::getCategoryForType($section->section_key),
            'parent_id' => null,
            'sort_order' => (int) $section->sort_order,
            'is_enabled' => (bool) $section->is_enabled,
            'show_on_desktop' => (bool) $section->show_on_desktop,
            'show_on_tablet' => (bool) $section->show_on_mobile,
            'show_on_mobile' => (bool) $section->show_on_mobile,
            'visible_from' => null,
            'visible_until' => null,
            'content' => $content,
            'data_source' => self::typeConfig($section->section_key)['data_source'] ?? null,
            'styles' => null,
            'custom_id' => null,
            'attributes' => null,
            'animation' => null,
        ];
    }

    private static function makeLegacyContentBlockPayload(ContentBlock $legacyBlock, string $pageType, mixed $pageId): array
    {
        $content = is_array($legacyBlock->content) ? $legacyBlock->content : [];
        $styles = self::legacyWrapperToStyles($content['_wrapper'] ?? []);
        unset($content['_wrapper']);

        $content['_legacy_content_block_id'] = $legacyBlock->id;
        if ($legacyBlock->section_key !== null && $legacyBlock->section_key !== '') {
            $content['_legacy_section_key'] = $legacyBlock->section_key;
        }

        return [
            'page_type' => $pageType,
            'page_id' => $pageId,
            'block_type' => $legacyBlock->block_type,
            'category' => self::getCategoryForType($legacyBlock->block_type),
            'parent_id' => null,
            'sort_order' => (int) $legacyBlock->sort_order,
            'is_enabled' => (bool) $legacyBlock->is_enabled,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'visible_from' => null,
            'visible_until' => null,
            'content' => $content,
            'data_source' => null,
            'styles' => $styles,
            'custom_id' => null,
            'attributes' => null,
            'animation' => null,
        ];
    }

    private static function existingComparableFingerprintCounts(string $pageType, mixed $pageId): array
    {
        return PageBlock::forPage($pageType, $pageId)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (PageBlock $block) => self::comparableBlockFingerprint(self::pageBlockComparablePayload($block)))
            ->countBy()
            ->map(fn ($count) => (int) $count)
            ->all();
    }

    private static function pageBlockComparablePayload(PageBlock $block): array
    {
        return [
            'block_type' => $block->block_type,
            'category' => $block->category,
            'parent_id' => $block->parent_id,
            'is_enabled' => (bool) $block->is_enabled,
            'show_on_desktop' => (bool) $block->show_on_desktop,
            'show_on_tablet' => (bool) $block->show_on_tablet,
            'show_on_mobile' => (bool) $block->show_on_mobile,
            'visible_from' => $block->visible_from?->toIso8601String(),
            'visible_until' => $block->visible_until?->toIso8601String(),
            'content' => $block->content ?? [],
            'data_source' => $block->data_source ?? null,
            'styles' => $block->styles ?? null,
            'custom_id' => $block->custom_id,
            'attributes' => $block->attributes ?? null,
            'animation' => $block->animation,
        ];
    }

    private static function comparableBlockFingerprint(array $payload): string
    {
        $fingerprintPayload = [
            'block_type' => $payload['block_type'] ?? null,
            'category' => $payload['category'] ?? null,
            'parent_id' => $payload['parent_id'] ?? null,
            'is_enabled' => (bool) ($payload['is_enabled'] ?? true),
            'show_on_desktop' => (bool) ($payload['show_on_desktop'] ?? true),
            'show_on_tablet' => (bool) ($payload['show_on_tablet'] ?? true),
            'show_on_mobile' => (bool) ($payload['show_on_mobile'] ?? true),
            'visible_from' => $payload['visible_from'] ?? null,
            'visible_until' => $payload['visible_until'] ?? null,
            'content' => self::normalizeComparableValue($payload['content'] ?? []),
            'data_source' => self::normalizeComparableValue($payload['data_source'] ?? null),
            'styles' => self::normalizeComparableValue($payload['styles'] ?? null),
            'custom_id' => $payload['custom_id'] ?? null,
            'attributes' => self::normalizeComparableValue($payload['attributes'] ?? null),
            'animation' => $payload['animation'] ?? null,
        ];

        return sha1(json_encode($fingerprintPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    private static function normalizeComparableValue(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        $normalized = [];
        foreach ($value as $key => $item) {
            if (is_string($key) && str_starts_with($key, '_legacy_')) {
                continue;
            }

            $normalized[$key] = self::normalizeComparableValue($item);
        }

        if (array_is_list($normalized)) {
            return $normalized;
        }

        ksort($normalized);

        return $normalized;
    }
}
