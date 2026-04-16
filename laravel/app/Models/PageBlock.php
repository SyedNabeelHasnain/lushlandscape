<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

/**
 * Unified page block model — replaces both PageSection and ContentBlock.
 *
 * Every block on every page is stored here. Blocks can be:
 *   • Data blocks (services_grid, testimonials, portfolio, etc.)
 *   • Content blocks (heading, paragraph, rich_text, etc.)
 *   • Layout blocks (two_column, three_column, tabs, etc.)
 *   • Media blocks (image, video, gallery, etc.)
 *   • Interactive blocks (cta, form, map, etc.)
 */
class PageBlock extends Model
{
    protected $fillable = [
        'page_type', 'page_id', 'block_type', 'category',
        'parent_id', 'sort_order',
        'is_enabled', 'show_on_desktop', 'show_on_tablet', 'show_on_mobile',
        'visible_from', 'visible_until',
        'content', 'data_source', 'styles',
        'custom_id', 'attributes', 'animation',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'show_on_desktop' => 'boolean',
            'show_on_tablet' => 'boolean',
            'show_on_mobile' => 'boolean',
            'visible_from' => 'datetime',
            'visible_until' => 'datetime',
            'content' => 'array',
            'data_source' => 'array',
            'styles' => 'array',
            'attributes' => 'array',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function scopeForPage($query, string $pageType, mixed $pageId)
    {
        // Treat 0 or '0' as null for home page consistency
        $pageId = ($pageId == 0 || $pageId === '0') ? null : $pageId;

        return $query->where('page_type', $pageType)
            ->where(function ($q) use ($pageId) {
                if ($pageId === null) {
                    $q->whereNull('page_id');
                } else {
                    $q->where('page_id', $pageId);
                }
            });
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeVisibleNow($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('visible_from')
                ->orWhere('visible_from', '<=', Carbon::now());
        })->where(function ($q) {
            $q->whereNull('visible_until')
                ->orWhere('visible_until', '>=', Carbon::now());
        });
    }

    public function scopeForDevice($query, string $device = 'all')
    {
        if ($device === 'desktop') {
            return $query->where('show_on_desktop', true);
        }
        if ($device === 'tablet') {
            return $query->where('show_on_tablet', true);
        }
        if ($device === 'mobile') {
            return $query->where('show_on_mobile', true);
        }

        return $query;
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    /**
     * Get the style value for a given device, falling back through the cascade.
     * Mobile -> Tablet -> Desktop
     */
    public function getStyle(string $key, string $device = 'desktop', mixed $default = null): mixed
    {
        $styles = $this->styles ?? [];

        // 1. Specific device override
        if (isset($styles[$device][$key]) && $styles[$device][$key] !== null) {
            return $styles[$device][$key];
        }

        // 2. Cascade fallback
        if ($device === 'mobile' && isset($styles['tablet'][$key]) && $styles['tablet'][$key] !== null) {
            return $styles['tablet'][$key];
        }

        if (($device === 'mobile' || $device === 'tablet') && isset($styles['desktop'][$key]) && $styles['desktop'][$key] !== null) {
            return $styles['desktop'][$key];
        }

        // 3. Global default from config if not found in instance styles
        $configDefaults = $this->category === 'theme'
            ? Config::get('blocks.theme_style_defaults.desktop', [])
            : Config::get('blocks.style_defaults.desktop', []);

        return $styles['desktop'][$key] ?? $configDefaults[$key] ?? $default;
    }

    /**
     * Get the merged data source configuration (global defaults + specific instance overrides).
     */
    public function getFinalDataSource(): array
    {
        $typeConfig = Config::get('blocks.types.'.$this->block_type, []);
        $globalDataSource = $typeConfig['data_source'] ?? [];
        $instanceDataSource = $this->data_source ?? [];

        return array_merge($globalDataSource, $instanceDataSource);
    }

    /**
     * Get the content for this block.
     */
    public function getContent(string $key, mixed $default = null): mixed
    {
        return $this->content[$key] ?? $default;
    }

    /**
     * Helper to check if block is a data-driven block.
     */
    public function isDataBlock(): bool
    {
        return $this->category === 'data' || ! empty($this->getFinalDataSource());
    }
}
