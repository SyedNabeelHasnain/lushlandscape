<?php

return [

    'official_media' => [
        'catalog_path' => storage_path('app/media-curated-catalog.json'),
    ],

    'openai' => [
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1/chat/completions'),
        'model' => env('OPENAI_MODEL', 'gpt-4o'),
    ],

    'maps' => [
        'js_url' => env('MAPS_JS_URL', 'https://maps.googleapis.com/maps/api/js'),
        'embed_url' => env('MAPS_EMBED_URL', 'https://www.google.com/maps/embed'),
    ],

];
