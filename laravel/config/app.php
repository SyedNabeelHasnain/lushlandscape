<?php

return [
    'name' => env('APP_NAME', 'Lush Landscape Service'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://lushlandscape.ca'),
    'timezone' => 'America/Toronto',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_CA',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],
    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Legacy Governance Strict Mode
    |--------------------------------------------------------------------------
    */
    'legacy_strict' => env('LEGACY_STRICT', false),

];
