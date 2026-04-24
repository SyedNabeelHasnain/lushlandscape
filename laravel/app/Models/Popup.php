<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'name', 'status', 'heading', 'body_content',
        'image_media_id', 'form_id',
        'trigger_type', 'trigger_delay_seconds', 'trigger_scroll_percent',
        'suppress_days', 'show_on_mobile', 'show_to_returning',
        'excluded_pages', 'starts_at', 'ends_at', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'excluded_pages' => 'array',
            'show_on_mobile' => 'boolean',
            'show_to_returning' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function image()
    {
        return $this->belongsTo(MediaAsset::class, 'image_media_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()));
    }
}
