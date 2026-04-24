<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'category_id', 'reviewer_name', 'reviewer_initial', 'reviewer_avatar_url', 'content', 'rating',
        'source', 'source_url', 'city_relevance', 'neighborhood_mention',
        'service_relevance', 'project_type', 'review_date', 'is_featured', 'status', 'sort_order',
    ];

    public function category()
    {
        return $this->belongsTo(ReviewCategory::class, 'category_id');
    }

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'review_date' => 'date',
            'rating' => 'integer',
        ];
    }
}
