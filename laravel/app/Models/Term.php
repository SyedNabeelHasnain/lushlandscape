<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'taxonomy_id',
        'parent_id',
        'name',
        'slug',
        'description',
        'data',
        'sort_order',
    ];

    protected $casts = [
        'data' => AsArrayObject::class,
    ];

    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function parent()
    {
        return $this->belongsTo(Term::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Term::class, 'parent_id');
    }

    public function entries()
    {
        return $this->morphedByMany(Entry::class, 'termable');
    }

    // --- Legacy Fallback Accessors for Frontend Compatibility ---

    public function getHeroMediaAttribute()
    {
        if (isset($this->data['hero_media_id'])) {
            return \App\Models\MediaAsset::find($this->data['hero_media_id']);
        }
        return null;
    }

    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function getSlugFinalAttribute()
    {
        return $this->slug;
    }

    public function getShortDescriptionAttribute()
    {
        return $this->description;
    }

    public function getLongDescriptionAttribute()
    {
        return $this->data['long_description'] ?? null;
    }

    public function getFrontendUrlAttribute()
    {
        if ($this->taxonomy && $this->taxonomy->slug === 'service-categories') {
            return url('/services/' . ltrim($this->slug, '/'));
        }
        if ($this->taxonomy && $this->taxonomy->slug === 'portfolio-categories') {
            return url('/portfolio/category/' . ltrim($this->slug, '/'));
        }
        if ($this->taxonomy && $this->taxonomy->slug === 'blog-categories') {
            return url('/blog/category/' . ltrim($this->slug, '/'));
        }
        return url('/' . $this->slug);
    }
}
