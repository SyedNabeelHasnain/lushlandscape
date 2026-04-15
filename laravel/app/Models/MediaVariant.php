<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaVariant extends Model
{
    protected $fillable = [
        'media_asset_id', 'variant_type', 'format', 'path',
        'file_size', 'width', 'height', 'quality',
    ];

    public function asset()
    {
        return $this->belongsTo(MediaAsset::class, 'media_asset_id');
    }
}
