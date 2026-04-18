<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

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
}
