<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int|null $category_id
 * @property int|null $author_id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $body
 * @property array|null $content_json
 * @property int|null $featured_image_id
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property array|null $schema_json
 * @property bool $is_featured
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property-read string|null $frontend_url
 * 
 * @property-read \App\Models\BlogCategory|null $category
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BlogTag[] $tags
 * @property-read \App\Models\MediaAsset|null $featuredImage
 * @property-read \App\Models\MediaAsset|null $heroMedia
 */
class BlogPost extends Model
{
    protected $fillable = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt', 'body',
        'content_json', 'featured_image_id', 'meta_title', 'meta_description',
        'og_title', 'og_description', 'schema_json', 'is_featured',
        'status', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'content_json' => 'array',
            'schema_json' => 'array',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_map');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    // Alias used by templates — maps to featured_image_id
    public function heroMedia(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'featured_image_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', \Illuminate\Support\Facades\DB::raw('NOW()'));
    }

    public function getFrontendUrlAttribute(): ?string
    {
        return $this->slug
            ? route('blog.show', ['slug' => $this->slug])
            : null;
    }
}
