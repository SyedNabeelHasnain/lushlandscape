<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_city_pages', function (Blueprint $table) {
            $table->foreignId('hero_media_id')->nullable()->after('local_intro')
                ->constrained('media_assets')->nullOnDelete();
        });

        Schema::table('static_pages', function (Blueprint $table) {
            $table->foreignId('hero_media_id')->nullable()->after('excerpt')
                ->constrained('media_assets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('service_city_pages', function (Blueprint $table) {
            $table->dropForeign(['hero_media_id']);
            $table->dropColumn('hero_media_id');
        });

        Schema::table('static_pages', function (Blueprint $table) {
            $table->dropForeign(['hero_media_id']);
            $table->dropColumn('hero_media_id');
        });
    }
};
