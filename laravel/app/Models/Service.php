<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $category_id
 * @property int|null $parent_id
 * @property string $name
 * @property string $system_slug
 * @property string|null $custom_slug
 * @property string $slug_final
 * @property string|null $navigation_label
 * @property string|null $service_code
 * @property string|null $service_summary
 * @property array|null $service_body
 * @property string|null $default_meta_title
 * @property string|null $default_meta_description
 * @property string|null $default_og_title
 * @property string|null $default_og_description
 * @property array|null $default_schema_json
 * @property string|null $icon
 * @property int|null $hero_media_id
 * @property string|null $hero_video_url
 * @property int|null $hero_image_2_media_id
 * @property int|null $hero_image_3_media_id
 * @property int|null $hero_image_4_media_id
 * @property array|null $keywords_json
 * @property string $status
 * @property int $sort_order
 * @property-read string|null $frontend_url
 * 
 * @property-read \App\Models\ServiceCategory|null $category
 * @property-read \App\Models\Service|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Service[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ServiceCityPage[] $cityPages
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ServiceCityPage[] $activeCityPages
 * @property-read \App\Models\MediaAsset|null $heroMedia
 * @property-read \App\Models\MediaAsset|null $heroImage2
 * @property-read \App\Models\MediaAsset|null $heroImage3
 * @property-read \App\Models\MediaAsset|null $heroImage4
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PortfolioProject[] $portfolioProjects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\City[] $cities
 */
class Service extends Model
{
    protected $fillable = [
        'category_id', 'parent_id', 'name', 'system_slug', 'custom_slug', 'slug_final',
        'navigation_label', 'service_code', 'service_summary', 'service_body',
        'default_meta_title', 'default_meta_description', 'default_og_title',
        'default_og_description', 'default_schema_json', 'icon', 'hero_media_id',
        'hero_video_url', 'hero_image_2_media_id', 'hero_image_3_media_id', 'hero_image_4_media_id',
        'keywords_json', 'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'service_body' => 'array',
            'default_schema_json' => 'array',
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
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function cityPages(): HasMany
    {
        return $this->hasMany(ServiceCityPage::class, 'service_id');
    }

    public function activeCityPages(): HasMany
    {
        return $this->hasMany(ServiceCityPage::class, 'service_id')->where('is_active', true);
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    public function heroImage2(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_image_2_media_id');
    }

    public function heroImage3(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_image_3_media_id');
    }

    public function heroImage4(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_image_4_media_id');
    }

    public function portfolioProjects(): HasMany
    {
        return $this->hasMany(PortfolioProject::class, 'service_id');
    }

    // Cities this service is explicitly assigned to (empty = global/all cities)
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'city_services')->withTimestamps();
    }

    public function getFrontendUrlAttribute(): ?string
    {
        if (! $this->slug_final || ! $this->category?->slug_final) {
            return null;
        }

        return route('services.detail', [
            'categorySlug' => $this->category->slug_final,
            'slug' => $this->slug_final,
        ]);
    }
}
