<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeLayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'conditions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'conditions' => 'array',
    ];

    protected static function booted(): void
    {
        static::saved(function (self $layout) {
            \Illuminate\Support\Facades\Cache::forget('global_theme_header');
            \Illuminate\Support\Facades\Cache::forget('global_theme_footer');

            if (! $layout->is_active) {
                return;
            }

            self::query()
                ->where('type', $layout->type)
                ->whereKeyNot($layout->getKey())
                ->where('is_active', true)
                ->update(['is_active' => false]);
        });

        static::deleted(function (self $layout) {
            \Illuminate\Support\Facades\Cache::forget('global_theme_header');
            \Illuminate\Support\Facades\Cache::forget('global_theme_footer');
        });
    }

    /**
     * Boot the model.
     * When a theme layout is set to active, we must ensure others of the same type are deactivated
     * if they are global (no conditions). For now, we just let them co-exist and resolve priority in service.
     */
}
