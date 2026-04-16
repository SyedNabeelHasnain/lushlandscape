<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string|null $province_name
 * @property string|null $region_name
 * @property string $name
 * @property string $system_slug
 * @property string|null $custom_slug
 * @property string $slug_final
 * @property string|null $navigation_label
 * @property string|null $city_summary
 * @property array|null $city_body
 * @property string|null $latitude
 * @property string|null $longitude
 * @property array|null $local_conditions_json
 * @property array|null $municipal_notes_json
 * @property string|null $default_meta_title
 * @property string|null $default_meta_description
 * @property string|null $default_og_title
 * @property string|null $default_og_description
 * @property array|null $default_schema_json
 * @property int|null $hero_media_id
 * @property string|null $hero_video_url
 * @property int|null $hero_image_2_media_id
 * @property int|null $hero_image_3_media_id
 * @property int|null $hero_image_4_media_id
 * @property array|null $keywords_json
 * @property string $status
 * @property int $sort_order
 * @property-read string|null $frontend_url
 * @property-read Collection|Neighborhood[] $neighborhoods
 * @property-read Collection|ServiceCityPage[] $servicePages
 * @property-read Collection|ServiceCityPage[] $activeServicePages
 * @property-read MediaAsset|null $heroMedia
 * @property-read MediaAsset|null $heroImage2
 * @property-read MediaAsset|null $heroImage3
 * @property-read MediaAsset|null $heroImage4
 * @property-read Collection|PortfolioProject[] $portfolioProjects
 * @property-read Collection|ServiceCategory[] $serviceCategories
 * @property-read Collection|Service[] $services
 */
class City extends Model
{
    protected $fillable = [
        'province_name', 'region_name', 'name', 'system_slug', 'custom_slug', 'slug_final',
        'navigation_label', 'city_summary', 'city_body', 'latitude', 'longitude',
        'local_conditions_json', 'municipal_notes_json',
        'default_meta_title', 'default_meta_description', 'default_og_title',
        'default_og_description', 'default_schema_json', 'hero_media_id',
        'hero_video_url', 'hero_image_2_media_id', 'hero_image_3_media_id', 'hero_image_4_media_id',
        'keywords_json', 'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'city_body' => 'array',
            'local_conditions_json' => 'array',
            'municipal_notes_json' => 'array',
            'default_schema_json' => 'array',
            'keywords_json' => 'array',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
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
            Cache::forget('global_cities_footer');
            Cache::forget('interactive_map_all_cities');
            Cache::forget('home_schema_cities');
        });

        static::deleted(function ($model) {
            Cache::forget('global_cities_footer');
            Cache::forget('interactive_map_all_cities');
            Cache::forget('home_schema_cities');
        });
    }

    public function neighborhoods(): HasMany
    {
        return $this->hasMany(Neighborhood::class)->orderBy('sort_order');
    }

    public function servicePages(): HasMany
    {
        return $this->hasMany(ServiceCityPage::class, 'city_id');
    }

    public function activeServicePages(): HasMany
    {
        return $this->hasMany(ServiceCityPage::class, 'city_id')->where('is_active', true);
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
        return $this->hasMany(PortfolioProject::class, 'city_id');
    }

    // Categories enabled for this city (controls which service groups appear on the city page)
    public function serviceCategories(): BelongsToMany
    {
        return $this->belongsToMany(ServiceCategory::class, 'city_service_categories')->withTimestamps();
    }

    // Services explicitly assigned to this city (empty on service = global)
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'city_services')->withTimestamps();
    }

    public function getFrontendUrlAttribute(): ?string
    {
        return $this->slug_final
            ? route('locations.city', ['slug' => $this->slug_final])
            : null;
    }
}
