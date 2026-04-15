<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int|null $service_id
 * @property int|null $city_id
 * @property int|null $neighborhood_id
 * @property string $system_slug
 * @property string|null $custom_slug
 * @property string $slug_final
 * @property string|null $navigation_label
 * @property string|null $page_title
 * @property string|null $h1
 * @property string|null $local_intro
 * @property int|null $hero_media_id
 * @property array|null $content_json
 * @property string|null $hero_video_url
 * @property int|null $hero_image_2_media_id
 * @property int|null $hero_image_3_media_id
 * @property int|null $hero_image_4_media_id
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property array|null $schema_json
 * @property array|null $keywords_json
 * @property array|null $cta_json
 * @property bool $is_active
 * @property bool $is_indexable
 * @property int $sort_order
 * @property-read string|null $frontend_url
 * 
 * @property-read \App\Models\Service|null $service
 * @property-read \App\Models\City|null $city
 * @property-read \App\Models\Neighborhood|null $neighborhood
 * @property-read \App\Models\MediaAsset|null $heroMedia
 * @property-read \App\Models\MediaAsset|null $heroImage2
 * @property-read \App\Models\MediaAsset|null $heroImage3
 * @property-read \App\Models\MediaAsset|null $heroImage4
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FaqAssignment[] $faqAssignments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MediaPlacement[] $mediaPlacements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Review[] $reviewAssignments
 */
class ServiceCityPage extends Model
{
    protected $fillable = [
        'service_id', 'city_id', 'neighborhood_id',
        'system_slug', 'custom_slug', 'slug_final',
        'navigation_label', 'page_title', 'h1', 'local_intro', 'hero_media_id', 'content_json',
        'hero_video_url', 'hero_image_2_media_id', 'hero_image_3_media_id', 'hero_image_4_media_id',
        'meta_title', 'meta_description', 'og_title', 'og_description',
        'schema_json', 'keywords_json', 'cta_json',
        'is_active', 'is_indexable', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'content_json' => 'array',
            'schema_json' => 'array',
            'keywords_json' => 'array',
            'cta_json' => 'array',
            'is_active' => 'boolean',
            'is_indexable' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (! $model->system_slug) {
                $service = Service::find($model->service_id);
                $city = City::find($model->city_id);
                if ($service && $city) {
                    $model->system_slug = Str::slug($service->name).'-'.Str::slug($city->name);
                } else {
                    // Fallback: prevent null slug_final DB constraint failure
                    $model->system_slug = 'page-'.($model->service_id ?? 0).'-'.($model->city_id ?? 0).'-'.Str::uuid();
                }
            }
            $model->slug_final = $model->custom_slug ?: $model->system_slug;
        });

        static::updating(function ($model) {
            $model->slug_final = $model->custom_slug ?: $model->system_slug;
        });
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class);
    }

    public function faqAssignments(): MorphMany
    {
        return $this->morphMany(FaqAssignment::class, 'assignable');
    }

    public function mediaPlacements(): MorphMany
    {
        return $this->morphMany(MediaPlacement::class, 'placeable');
    }

    public function reviewAssignments(): MorphToMany
    {
        return $this->morphToMany(Review::class, 'assignable', 'review_assignments');
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

    public function getFrontendUrlAttribute(): ?string
    {
        return $this->slug_final
            ? url('/'.$this->slug_final)
            : null;
    }
}
