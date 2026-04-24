<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\IsTaxonomyTerm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaqCategory extends Model
{
    use IsTaxonomyTerm;

    protected $fillable = [
        'parent_id', 'name', 'slug', 'short_description', 'description',
        'icon', 'image_media_id', 'og_title', 'og_description',
        'meta_title', 'meta_description', 'schema_type', 'schema_json',
        'language', 'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return ['schema_json' => 'array'];
    }

    public function faqs(): HasMany
    {
        // 'display_order' is a column on the faqs table (unchanged)
        return $this->hasMany(Faq::class, 'category_id')->orderBy('display_order');
    }
}
