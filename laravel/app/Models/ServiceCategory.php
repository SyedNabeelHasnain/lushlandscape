<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $system_slug
 * @property string|null $custom_slug
 * @property string $slug_final
 * @property string|null $navigation_label
 * @property string|null $short_description
 * @property string|null $long_description
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property array|null $schema_json
 * @property array|null $keywords_json
 * @property int|null $hero_media_id
 * @property string|null $icon
 * @property string $status
 * @property int $sort_order
 * @property-read string|null $frontend_url
 * 
 * @property-read \App\Models\ServiceCategory|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ServiceCategory[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Service[] $services
 * @property-read \App\Models\MediaAsset|null $heroMedia
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 */
class ServiceCategory extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'system_slug', 'custom_slug', 'slug_final',
        'navigation_label', 'short_description', 'long_description',
        'meta_title', 'meta_description', 'og_title', 'og_description',
        'schema_json', 'keywords_json', 'hero_media_id', 'icon', 'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'schema_json' => 'array',
            'keywords_json' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->system_slug = $model->system_slug ?: Str::slug($model->name);
            $model->slug_final = $model->custom_slug ?: $model->system_slug;
            $model->navigation_label = $model->navigation_label ?: $model->name;
        });

        static::updating(function ($model) {
            $model->slug_final = $model->custom_slug ?: $model->system_slug;
        });

        static::saved(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('global_service_categories_footer');
            \Illuminate\Support\Facades\Cache::forget('mega_nav_categories_v2');
        });

        static::deleted(function ($model) {
            \Illuminate\Support\Facades\Cache::forget('global_service_categories_footer');
            \Illuminate\Support\Facades\Cache::forget('mega_nav_categories_v2');
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'category_id')->orderBy('sort_order');
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    // Cities where this category is enabled
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_service_categories')->withTimestamps();
    }

    public function getFrontendUrlAttribute(): ?string
    {
        return $this->slug_final
            ? route('services.category', ['slug' => $this->slug_final])
            : null;
    }
}
