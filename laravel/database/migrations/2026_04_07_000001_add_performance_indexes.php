<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Search analytics — composite index for period-based queries
        Schema::table('search_logs', function (Blueprint $table) {
            $table->index(['created_at', 'query'], 'search_logs_created_query_idx');
            $table->index(['results_count', 'created_at'], 'search_logs_results_created_idx');
        });

        // Form submissions — for dashboard filtering
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->index(['form_id', 'status', 'created_at'], 'form_submissions_form_status_created_idx');
        });

        // Service-city pages — for page resolution
        Schema::table('service_city_pages', function (Blueprint $table) {
            $table->index(['is_active', 'is_indexable'], 'scp_active_indexable_idx');
            $table->index(['city_id', 'service_id'], 'scp_city_service_idx');
        });

        // Blog posts — for blog queries
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index(['status', 'published_at', 'category_id'], 'blog_posts_status_published_cat_idx');
        });

        // Redirects — for middleware lookups
        Schema::table('redirects', function (Blueprint $table) {
            $table->index(['old_url', 'is_active'], 'redirects_url_active_idx');
        });

        // Media assets — for duplicate detection
        Schema::table('media_assets', function (Blueprint $table) {
            $table->index('checksum', 'media_assets_checksum_idx');
        });

        // FAQs — for featured/pinned ordering
        Schema::table('faqs', function (Blueprint $table) {
            $table->index(['status', 'is_featured', 'is_pinned'], 'faqs_status_featured_pinned_idx');
        });

        // Reviews — for published/featured queries
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['status', 'is_featured', 'review_date'], 'reviews_status_featured_date_idx');
        });
    }

    public function down(): void
    {
        Schema::table('search_logs', function (Blueprint $table) {
            $table->dropIndex('search_logs_created_query_idx');
            $table->dropIndex('search_logs_results_created_idx');
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropIndex('form_submissions_form_status_created_idx');
        });

        Schema::table('service_city_pages', function (Blueprint $table) {
            $table->dropIndex('scp_active_indexable_idx');
            $table->dropIndex('scp_city_service_idx');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('blog_posts_status_published_cat_idx');
        });

        Schema::table('redirects', function (Blueprint $table) {
            $table->dropIndex('redirects_url_active_idx');
        });

        Schema::table('media_assets', function (Blueprint $table) {
            $table->dropIndex('media_assets_checksum_idx');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex('faqs_status_featured_pinned_idx');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_status_featured_date_idx');
        });
    }
};
