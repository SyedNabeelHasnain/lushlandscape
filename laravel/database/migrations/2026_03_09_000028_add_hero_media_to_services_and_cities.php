<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->foreignId('hero_media_id')->nullable()->after('icon')
                ->constrained('media_assets')->nullOnDelete();
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreignId('hero_media_id')->nullable()->after('default_schema_json')
                ->constrained('media_assets')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['hero_media_id']);
            $table->dropColumn('hero_media_id');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign(['hero_media_id']);
            $table->dropColumn('hero_media_id');
        });
    }
};
