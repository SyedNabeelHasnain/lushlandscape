<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaPlacement extends Model
{
    protected $fillable = [
        'media_asset_id', 'placeable_type', 'placeable_id', 'slot',
        'alt_override', 'caption_override', 'description_override',
        'is_decorative', 'crop_data', 'loading', 'fetchpriority',
        'schema_eligible', 'social_preview', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'crop_data' => 'array',
            'is_decorative' => 'boolean',
            'schema_eligible' => 'boolean',
            'social_preview' => 'boolean',
        ];
    }

    public function asset()
    {
        return $this->belongsTo(MediaAsset::class, 'media_asset_id');
    }

    public function placeable()
    {
        return $this->morphTo();
    }

    public function getAltTextAttribute(): string
    {
        if ($this->is_decorative) {
            return '';
        }

        return $this->alt_override ?: ($this->asset->default_alt_text ?? '');
    }
}
