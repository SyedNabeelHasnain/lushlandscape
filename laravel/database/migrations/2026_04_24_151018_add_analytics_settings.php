<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            [
                'key' => 'analytics_gtm_id',
                'label' => 'Google Tag Manager ID',
                'description' => 'e.g. GTM-XXXXXXX',
                'type' => 'text',
                'group' => 'analytics',
                'sort_order' => 10,
                'value' => '',
            ],
            [
                'key' => 'analytics_ga4_id',
                'label' => 'Google Analytics 4 ID',
                'description' => 'e.g. G-XXXXXXXXXX',
                'type' => 'text',
                'group' => 'analytics',
                'sort_order' => 20,
                'value' => '',
            ],
            [
                'key' => 'analytics_fb_pixel_id',
                'label' => 'Meta (Facebook) Pixel ID',
                'description' => 'e.g. 1234567890',
                'type' => 'text',
                'group' => 'analytics',
                'sort_order' => 30,
                'value' => '',
            ],
        ];

        DB::table('settings')->insertOrIgnore($settings);
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', [
            'analytics_gtm_id',
            'analytics_ga4_id',
            'analytics_fb_pixel_id',
        ])->delete();
    }
};
