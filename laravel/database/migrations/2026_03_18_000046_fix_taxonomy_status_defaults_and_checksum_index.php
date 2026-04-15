<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix faq_categories: default 'active' → 'published', update existing rows
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->string('status')->default('published')->change();
        });
        DB::table('faq_categories')->where('status', 'active')->update(['status' => 'published']);

        // Fix blog_categories: default 'active' → 'published', update existing rows
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('status')->default('published')->change();
        });
        DB::table('blog_categories')->where('status', 'active')->update(['status' => 'published']);

        // Add index on media_assets.checksum for upload duplicate detection
        Schema::table('media_assets', function (Blueprint $table) {
            $table->index('checksum', 'idx_media_assets_checksum');
        });
    }

    public function down(): void
    {
        Schema::table('faq_categories', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });

        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('status')->default('active')->change();
        });

        Schema::table('media_assets', function (Blueprint $table) {
            $table->dropIndex('idx_media_assets_checksum');
        });
    }
};
