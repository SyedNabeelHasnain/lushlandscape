<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Services: status + sort_order for filtered listings
        Schema::table('services', function (Blueprint $table) {
            $table->index(['status', 'sort_order'], 'idx_services_status_sort');
        });

        // Service categories: status + sort_order
        Schema::table('service_categories', function (Blueprint $table) {
            $table->index(['status', 'sort_order'], 'idx_service_categories_status_sort');
        });

        // Cities: status + sort_order
        Schema::table('cities', function (Blueprint $table) {
            $table->index(['status', 'sort_order'], 'idx_cities_status_sort');
        });

        // Service-city pages: is_active for activeServicePages scope
        Schema::table('service_city_pages', function (Blueprint $table) {
            $table->index('is_active', 'idx_scp_is_active');
            $table->index(['city_id', 'is_active'], 'idx_scp_city_active');
            $table->index(['service_id', 'is_active'], 'idx_scp_service_active');
        });

        // Blog posts: status + published_at for listing queries
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index(['status', 'published_at'], 'idx_blog_posts_status_published');
        });

        // FAQs: category + display_order for section rendering
        Schema::table('faqs', function (Blueprint $table) {
            $table->index(['status', 'display_order'], 'idx_faqs_status_order');
            $table->index(['category_id', 'display_order'], 'idx_faqs_category_order');
        });

        // Reviews: status + sort_order for testimonials section
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['status', 'sort_order'], 'idx_reviews_status_sort');
        });

        // Portfolio projects: status + sort_order
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->index(['status', 'sort_order'], 'idx_portfolio_status_sort');
        });

        // Redirects: old_url + is_active for middleware lookup
        Schema::table('redirects', function (Blueprint $table) {
            $table->index(['old_url', 'is_active'], 'idx_redirects_old_url_active');
        });

        // Security rules: type + action + is_active for middleware lookup
        Schema::table('security_rules', function (Blueprint $table) {
            $table->index(['type', 'action', 'is_active'], 'idx_security_rules_lookup');
        });

        // Content blocks: page_type + page_id + is_enabled for ContentBlockService
        Schema::table('page_content_blocks', function (Blueprint $table) {
            $table->index(['page_type', 'page_id', 'is_enabled'], 'idx_content_blocks_lookup');
        });

        // FULLTEXT indexes for search functionality
        Schema::table('services', function (Blueprint $table) {
            $table->fullText(['name', 'service_summary'], 'ft_services_search');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->fullText(['name', 'region_name'], 'ft_cities_search');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->fullText(['title', 'excerpt'], 'ft_blog_posts_search');
        });

        Schema::table('faqs', function (Blueprint $table) {
            $table->fullText(['question', 'answer'], 'ft_faqs_search');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('idx_services_status_sort');
            $table->dropFullText('ft_services_search');
        });
        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropIndex('idx_service_categories_status_sort');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex('idx_cities_status_sort');
            $table->dropFullText('ft_cities_search');
        });
        Schema::table('service_city_pages', function (Blueprint $table) {
            $table->dropIndex('idx_scp_is_active');
            $table->dropIndex('idx_scp_city_active');
            $table->dropIndex('idx_scp_service_active');
        });
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropIndex('idx_blog_posts_status_published');
            $table->dropFullText('ft_blog_posts_search');
        });
        Schema::table('faqs', function (Blueprint $table) {
            $table->dropIndex('idx_faqs_status_order');
            $table->dropIndex('idx_faqs_category_order');
            $table->dropFullText('ft_faqs_search');
        });
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('idx_reviews_status_sort');
        });
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->dropIndex('idx_portfolio_status_sort');
        });
        Schema::table('redirects', function (Blueprint $table) {
            $table->dropIndex('idx_redirects_old_url_active');
        });
        Schema::table('security_rules', function (Blueprint $table) {
            $table->dropIndex('idx_security_rules_lookup');
        });
        Schema::table('page_content_blocks', function (Blueprint $table) {
            $table->dropIndex('idx_content_blocks_lookup');
        });
    }
};
