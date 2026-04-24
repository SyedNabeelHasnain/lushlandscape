<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Entry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content_type_id',
        'parent_id',
        'title',
        'slug',
        'status',
        'author_id',
        'published_at',
        'data',
        'sort_order',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'data' => AsArrayObject::class, // The Hybrid JSON Engine
    ];

    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }

    public function parent()
    {
        return $this->belongsTo(Entry::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Entry::class, 'parent_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // The Universal Routing alias
    public function routeAlias()
    {
        return $this->morphOne(RouteAlias::class, 'routable');
    }

    // Taxonomy Term assignment
    public function terms()
    {
        return $this->morphToMany(Term::class, 'termable');
    }

    // Outbound relationships
    public function relatedEntries()
    {
        return $this->belongsToMany(Entry::class, 'entry_relations', 'source_entry_id', 'target_entry_id')
            ->withPivot('relation_type', 'sort_order')
            ->orderByPivot('sort_order');
    }

    // Inbound relationships
    public function inverseRelatedEntries()
    {
        return $this->belongsToMany(Entry::class, 'entry_relations', 'target_entry_id', 'source_entry_id')
            ->withPivot('relation_type', 'sort_order');
    }

    // --- Legacy Fallback Accessors for Frontend Compatibility ---

    public function getHeroMediaAttribute()
    {
        if (isset($this->data['hero_media_id'])) {
            return MediaAsset::find($this->data['hero_media_id']);
        }
        if (isset($this->data['featured_image_id'])) {
            return MediaAsset::find($this->data['featured_image_id']);
        }

        return null;
    }

    public function getCategoryAttribute()
    {
        return $this->terms->first();
    }

    public function getServiceAttribute()
    {
        return $this->relatedEntries->firstWhere('pivot.relation_type', 'portfolio_service')
            ?? $this->relatedEntries->firstWhere('pivot.relation_type', 'matrix_service');
    }

    public function getCityAttribute()
    {
        return $this->relatedEntries->firstWhere('pivot.relation_type', 'portfolio_city')
            ?? $this->relatedEntries->firstWhere('pivot.relation_type', 'matrix_city');
    }

    public function getNameAttribute()
    {
        return $this->title;
    }

    public function getSlugFinalAttribute()
    {
        return $this->slug;
    }

    public function getProvinceNameAttribute()
    {
        return $this->data['province_name'] ?? null;
    }

    public function getCitySummaryAttribute()
    {
        return $this->data['city_summary'] ?? null;
    }

    public function getServiceSummaryAttribute()
    {
        return $this->data['service_summary'] ?? null;
    }

    public function getExcerptAttribute()
    {
        return $this->data['excerpt'] ?? null;
    }

    public function getBodyAttribute()
    {
        return $this->data['body'] ?? null;
    }

    public function getDescriptionAttribute()
    {
        return $this->data['description'] ?? null;
    }

    public function getFrontendUrlAttribute()
    {
        return $this->routeAlias ? url('/'.ltrim($this->routeAlias->slug, '/')) : url('/'.$this->slug);
    }

    public function getLatitudeAttribute()
    {
        return $this->data['latitude'] ?? null;
    }

    public function getLongitudeAttribute()
    {
        return $this->data['longitude'] ?? null;
    }

    public function getNeighborhoodsAttribute()
    {
        $hoods = $this->data['city_body']['neighborhoods_served'] ?? [];
        if (! is_array($hoods)) {
            return collect();
        }

        return collect($hoods)->map(function ($hood) {
            return (object) [
                'name' => $hood,
                'slug' => Str::slug($hood),
                'latitude' => $this->data['latitude'] ?? null,
                'longitude' => $this->data['longitude'] ?? null,
                'summary' => '',
            ];
        });
    }
}
