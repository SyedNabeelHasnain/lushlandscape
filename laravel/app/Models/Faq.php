<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $category_id
 * @property string $slug
 * @property string $question
 * @property string|null $short_answer
 * @property string $answer
 * @property string $answer_format
 * @property string $faq_type
 * @property string $audience_type
 * @property string $language
 * @property string|null $seo_title
 * @property string|null $meta_description
 * @property string|null $og_title
 * @property string|null $og_description
 * @property string|null $chatbot_summary
 * @property array|null $alternate_phrasings
 * @property array|null $semantic_keywords
 * @property string $search_intent_type
 * @property bool $is_featured
 * @property bool $is_pinned
 * @property bool $local_relevance
 * @property string|null $city_relevance
 * @property string|null $region_relevance
 * @property bool $schema_eligible
 * @property int $helpful_count
 * @property int $not_helpful_count
 * @property string $status
 * @property int $display_order
 * @property Carbon|null $published_at
 * @property Carbon|null $review_date
 * @property Carbon|null $expiry_date
 * @property-read string|null $frontend_url
 * @property-read FaqCategory|null $category
 * @property-read Collection|FaqAssignment[] $assignments
 * @property-read Collection|FaqFeedback[] $feedback
 * @property-read Collection|FaqTag[] $tags
 */
class Faq extends Model
{
    protected $fillable = [
        'category_id', 'slug', 'question', 'short_answer', 'answer', 'answer_format',
        'faq_type', 'audience_type', 'language', 'seo_title', 'meta_description',
        'og_title', 'og_description', 'chatbot_summary', 'alternate_phrasings',
        'semantic_keywords', 'search_intent_type', 'is_featured', 'is_pinned',
        'local_relevance', 'city_relevance', 'region_relevance', 'schema_eligible',
        'helpful_count', 'not_helpful_count', 'status', 'display_order',
        'published_at', 'review_date', 'expiry_date',
    ];

    protected function casts(): array
    {
        return [
            'alternate_phrasings' => 'array',
            'semantic_keywords' => 'array',
            'is_featured' => 'boolean',
            'is_pinned' => 'boolean',
            'local_relevance' => 'boolean',
            'schema_eligible' => 'boolean',
            'published_at' => 'datetime',
            'review_date' => 'datetime',
            'expiry_date' => 'datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'category_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(FaqAssignment::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(FaqFeedback::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(FaqTag::class, 'faq_tag_map');
    }

    public function getFrontendUrlAttribute(): ?string
    {
        return $this->id
            ? url('/faqs#faq-'.$this->id)
            : null;
    }
}
