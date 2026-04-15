<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->foreignId('hero_media_id')->nullable()->after('neighborhood')
                ->constrained('media_assets')->nullOnDelete();
            $table->foreignId('before_image_id')->nullable()->after('hero_media_id')
                ->constrained('media_assets')->nullOnDelete();
            $table->foreignId('after_image_id')->nullable()->after('before_image_id')
                ->constrained('media_assets')->nullOnDelete();
            // gallery_media_ids stores array of media_asset IDs (replaces plain URL array)
            $table->json('gallery_media_ids')->nullable()->after('gallery_images');
            $table->string('project_value_range')->nullable()->after('video_url');
            $table->string('project_duration')->nullable()->after('project_value_range');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->string('reviewer_avatar_url')->nullable()->after('reviewer_initial');
            $table->string('project_type')->nullable()->after('service_relevance');
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->dropForeign(['hero_media_id']);
            $table->dropForeign(['before_image_id']);
            $table->dropForeign(['after_image_id']);
            $table->dropColumn(['hero_media_id', 'before_image_id', 'after_image_id', 'gallery_media_ids', 'project_value_range', 'project_duration']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['reviewer_avatar_url', 'project_type']);
        });
    }
};
