<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Setting extends Model
{
    protected $fillable = [
        'group', 'key', 'value', 'type', 'label', 'description', 'is_public', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    private const CACHE_KEY = 'lush_settings_all';

    private const CACHE_TTL = 3600; // 1 hour

    // In-memory cache — one DB query per request instead of one per call
    private static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::getAll();

        return $all[$key] ?? $default;
    }

    public static function getAll(): array
    {
        if (empty(self::$cache)) {
            try {
                self::$cache = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, fn () => static::pluck('value', 'key')->all());
            } catch (\Throwable $e) {
                Log::error('Failed to load settings cache: '.$e->getMessage());
                self::$cache = [];
            }
        }

        return self::$cache;
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cache[$key] = $value;
        Cache::forget(self::CACHE_KEY);
    }

    /** Bust in-memory and persistent cache after bulk updates. */
    public static function flushCache(): void
    {
        self::$cache = [];
        Cache::forget(self::CACHE_KEY);
    }
}
