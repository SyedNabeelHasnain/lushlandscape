<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentBlock extends Model
{
    protected $table = 'page_content_blocks';

    protected $fillable = [
        'page_type', 'page_id', 'section_key',
        'block_type', 'sort_order', 'is_enabled', 'content',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'content' => 'array',
        ];
    }
}
