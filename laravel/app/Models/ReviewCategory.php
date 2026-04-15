<?php

namespace App\Models;

use App\Models\Concerns\IsTaxonomyTerm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReviewCategory extends Model
{
    use IsTaxonomyTerm;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'short_description', 'description',
        'image_media_id', 'og_title', 'og_description',
        'meta_title', 'meta_description', 'schema_type', 'schema_json',
        'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['schema_json' => 'array'];
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'category_id');
    }
}
