<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $page_type
 * @property string|null $excerpt
 * @property int|null $hero_media_id
 * @property string|null $body
 * @property array|null $content_json
 * @property string $template
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property array|null $schema_json
 * @property bool $is_indexable
 * @property string $status
 * @property int $sort_order
 * @property-read string|null $frontend_url
 * 
 * @property-read \App\Models\MediaAsset|null $heroMedia
 */
class StaticPage extends Model
{
    protected $fillable = [
        'title', 'slug', 'page_type', 'excerpt', 'hero_media_id', 'body', 'content_json',
        'template', 'meta_title', 'meta_description', 'og_title', 'og_description',
        'schema_json', 'is_indexable', 'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'content_json' => 'array',
            'schema_json' => 'array',
            'is_indexable' => 'boolean',
        ];
    }

    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'hero_media_id');
    }

    public function getFrontendUrlAttribute(): ?string
    {
        return $this->slug
            ? url('/'.$this->slug)
            : null;
    }
}
