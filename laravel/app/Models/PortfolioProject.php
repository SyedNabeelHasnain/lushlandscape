<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property array|null $gallery_media_ids
 * @property array|null $gallery_images
 * @property array|null $schema_json
 * @property bool $is_featured
 * @property Carbon|null $completion_date
 * @property-read string|null $frontend_url
 */
class PortfolioProject extends Model
{
    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 'body', 'project_type',
        'city_id', 'service_id', 'neighborhood',
        'hero_media_id', 'before_image_id', 'after_image_id',
        'gallery_images', 'gallery_media_ids', 'video_url',
        'project_value_range', 'project_duration',
        'meta_title', 'meta_description', 'schema_json', 'is_featured',
        'status', 'completion_date', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'gallery_images' => 'array',
            'gallery_media_ids' => 'array',
            'schema_json' => 'array',
            'is_featured' => 'boolean',
            'completion_date' => 'date',
        ];
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'category_id');
    }

    public function heroMedia(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    public function beforeImage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'before_image_id');
    }

    public function afterImage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'after_image_id');
    }

    // Returns MediaAsset models for gallery (media-library based)
    public function galleryMedia()
    {
        if (empty($this->gallery_media_ids)) {
            return collect();
        }

        $media = MediaAsset::whereIn('id', $this->gallery_media_ids)->get();

        // Sort by original gallery_media_ids order in PHP for database portability
        $order = array_flip($this->gallery_media_ids);

        return $media->sortBy(fn ($m) => $order[$m->id] ?? PHP_INT_MAX)->values();
    }

    public function getFrontendUrlAttribute(): ?string
    {
        return $this->slug
            ? route('portfolio.show', ['slug' => $this->slug])
            : null;
    }
}
