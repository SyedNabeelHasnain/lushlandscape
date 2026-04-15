<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Unified page blocks table — replaces both page_sections and page_content_blocks.
     *
     * Every block (whether a data-driven section like "Services Grid" or a content
     * block like "Heading") lives here. Blocks can be nested via parent_id.
     */
    public function up(): void
    {
        Schema::create('page_blocks', function (Blueprint $table) {
            $table->id();

            // Owner: which page this block belongs to
            $table->string('page_type', 60);          // 'home', 'city', 'service', 'service_city_page', 'static_page', 'blog_post', 'portfolio_project', etc.
            $table->unsignedBigInteger('page_id')->nullable(); // null = singleton (home) or template default

            // Block identity
            $table->string('block_type', 80);         // 'hero', 'services_grid', 'testimonials', 'heading', 'two_column', etc.
            $table->string('category', 30)->default('content'); // 'data', 'content', 'layout', 'media', 'interactive'

            // Hierarchy (nesting support)
            $table->unsignedBigInteger('parent_id')->nullable(); // for nested blocks (e.g., columns inside a row)
            $table->unsignedSmallInteger('sort_order')->default(0);

            // Visibility
            $table->boolean('is_enabled')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->boolean('show_on_tablet')->default(true);
            $table->boolean('show_on_mobile')->default(true);
            $table->timestamp('visible_from')->nullable();
            $table->timestamp('visible_until')->nullable();

            // Content: static content for content blocks
            $table->json('content')->nullable();

            // Data source: for dynamic/data blocks
            $table->json('data_source')->nullable();
            /*
             * data_source structure:
             * {
             *   "model": "App\\Models\\Service",           // Eloquent model
             *   "scope": "published",                      // scope to apply
             *   "filters": {                               // query filters
             *     "category_id": "auto",                   // "auto" = from page context, or specific ID, or "all"
             *     "city_id": "auto",
             *     "status": "published"
             *   },
             *   "limit": 8,                                // max records
             *   "order_by": "sort_order",                  // column to order by
             *   "order_dir": "asc",                        // asc or desc
             *   "manual_ids": [],                          // specific IDs (overrides filters)
             *   "override_heading": "",                    // custom heading
             *   "override_subtitle": "",                   // custom subtitle
             * }
             */

            // Styling (responsive)
            $table->json('styles')->nullable();
            /*
             * styles structure (desktop / tablet / mobile):
             * {
             *   "desktop": {
             *     "bg_color": "none",
             *     "bg_image_id": null,
             *     "bg_overlay": "none",
             *     "text_color": "default",
             *     "padding_top": "lg",
             *     "padding_bottom": "lg",
             *     "padding_left": "md",
             *     "padding_right": "md",
             *     "margin_top": "none",
             *     "margin_bottom": "lg",
             *     "max_width": "full",
             *     "rounded": false,
             *     "border": "none",
             *     "shadow": "none",
             *     "custom_class": ""
             *   },
             *   "tablet": { ... },  // inherits from desktop if not set
             *   "mobile": { ... }   // inherits from desktop if not set
             * }
             */

            // Advanced
            $table->string('custom_id', 100)->nullable();    // custom HTML id attribute
            $table->json('attributes')->nullable();           // custom HTML attributes
            $table->string('animation', 40)->nullable();      // GSAP animation preset

            $table->timestamps();

            // Indexes
            $table->index(['page_type', 'page_id', 'sort_order'], 'page_blocks_page_sort_idx');
            $table->index(['page_type', 'page_id', 'category'], 'page_blocks_page_cat_idx');
            $table->index(['page_type', 'page_id', 'is_enabled'], 'page_blocks_page_enabled_idx');
            $table->index('parent_id', 'page_blocks_parent_idx');
            $table->index('block_type', 'page_blocks_type_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_blocks');
    }
};
