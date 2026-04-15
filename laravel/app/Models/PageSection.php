<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    protected $fillable = [
        'page_type', 'page_id', 'section_key',
        'is_enabled', 'show_on_desktop', 'show_on_mobile',
        'sort_order', 'settings',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'show_on_desktop' => 'boolean',
            'show_on_mobile' => 'boolean',
            'settings' => 'array',
        ];
    }
}
