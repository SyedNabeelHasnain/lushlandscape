<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Safely drop all legacy hardcoded tables
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('service_city_pages');
        Schema::dropIfExists('city_services');
        Schema::dropIfExists('city_service_categories');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');

        Schema::dropIfExists('portfolio_projects');
        Schema::dropIfExists('portfolio_categories');

        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_post_tags');
        Schema::dropIfExists('blog_categories');

        Schema::dropIfExists('static_pages');
        Schema::dropIfExists('neighborhoods');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // This migration is irreversible in the context of the new Super WMS
    }
};
