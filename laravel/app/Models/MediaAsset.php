<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $internal_title
 * @property string $canonical_filename
 * @property string $disk
 * @property string $path
 * @property string $media_type
 * @property string|null $editorial_class
 * @property string $mime_type
 * @property string $extension
 * @property int $file_size
 * @property int|null $width
 * @property int|null $height
 * @property float|null $aspect_ratio
 * @property string|null $orientation
 * @property string|null $duration
 * @property string|null $description
 * @property string|null $default_alt_text
 * @property string|null $default_caption
 * @property string|null $credit
 * @property string|null $language
 * @property string|null $image_purpose
 * @property array|null $focal_point
 * @property bool $text_present
 * @property bool $social_preview_eligible
 * @property bool $schema_eligible
 * @property string|null $location_city
 * @property string|null $location_region
 * @property string|null $checksum
 * @property array|null $tags
 * @property string $status
 * @property string|null $source_url
 * @property string $url
 */
class MediaAsset extends Model
{
    protected $appends = ['url'];

    protected $fillable = [
        'internal_title', 'canonical_filename', 'disk', 'path', 'media_type',
        'editorial_class', 'mime_type', 'extension', 'file_size', 'width', 'height',
        'aspect_ratio', 'orientation', 'duration', 'description', 'default_alt_text',
        'default_caption', 'credit', 'language', 'image_purpose', 'focal_point',
        'text_present', 'social_preview_eligible', 'schema_eligible',
        'location_city', 'location_region', 'checksum', 'tags', 'status',
        'source_url',
    ];

    protected function casts(): array
    {
        return [
            'focal_point' => 'array',
            'tags' => 'array',
            'text_present' => 'boolean',
            'social_preview_eligible' => 'boolean',
            'schema_eligible' => 'boolean',
        ];
    }

    public function variants()
    {
        return $this->hasMany(MediaVariant::class);
    }

    public function placements()
    {
        return $this->hasMany(MediaPlacement::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/'.$this->path);
    }
}
