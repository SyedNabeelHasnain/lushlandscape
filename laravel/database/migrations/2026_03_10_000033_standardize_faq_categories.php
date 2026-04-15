<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            // Standardise column names to match the shared taxonomy schema
            $table->renameColumn('title', 'name');
            $table->renameColumn('seo_title', 'meta_title');
            $table->renameColumn('short_summary', 'short_description');
            $table->renameColumn('display_order', 'sort_order');

            // Add fields that were missing
            $table->unsignedBigInteger('image_media_id')->nullable()->after('icon');
            $table->string('schema_type', 100)->default('FAQPage')->after('og_description');
            $table->json('schema_json')->nullable()->after('schema_type');

            $table->foreign('image_media_id')
                ->references('id')->on('media_assets')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->dropForeign(['image_media_id']);
            $table->dropColumn(['image_media_id', 'schema_type', 'schema_json']);
            $table->renameColumn('name', 'title');
            $table->renameColumn('meta_title', 'seo_title');
            $table->renameColumn('short_description', 'short_summary');
            $table->renameColumn('sort_order', 'display_order');
        });
    }
};
