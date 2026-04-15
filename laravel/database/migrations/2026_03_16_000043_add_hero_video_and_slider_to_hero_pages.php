<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['cities', 'services', 'service_city_pages'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->string('hero_video_url', 500)->nullable()->after('hero_media_id');
                $table->foreignId('hero_image_2_media_id')->nullable()->after('hero_video_url')->constrained('media_assets')->nullOnDelete();
                $table->foreignId('hero_image_3_media_id')->nullable()->after('hero_image_2_media_id')->constrained('media_assets')->nullOnDelete();
                $table->foreignId('hero_image_4_media_id')->nullable()->after('hero_image_3_media_id')->constrained('media_assets')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['cities', 'services', 'service_city_pages'] as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropForeign(['hero_image_2_media_id']);
                $table->dropForeign(['hero_image_3_media_id']);
                $table->dropForeign(['hero_image_4_media_id']);
                $table->dropColumn(['hero_video_url', 'hero_image_2_media_id', 'hero_image_3_media_id', 'hero_image_4_media_id']);
            });
        }
    }
};
