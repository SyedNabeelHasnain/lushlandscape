<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add FK + index on blog_posts.featured_image_id
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->index('featured_image_id');
            $table->foreign('featured_image_id')->references('id')->on('media_assets')->nullOnDelete();
        });

        // Add FK + index on service_categories.hero_media_id
        Schema::table('service_categories', function (Blueprint $table) {
            $table->index('hero_media_id');
            $table->foreign('hero_media_id')->references('id')->on('media_assets')->nullOnDelete();
        });

        // Change faqs.category_id from cascadeOnDelete to restrictOnDelete
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('faqs', function (Blueprint $table) {
                $table->dropForeign('faqs_category_id_foreign');
                $table->foreign('category_id')->references('id')->on('faq_categories')->restrictOnDelete();
            });

            // Change form_submissions.form_id from cascadeOnDelete to restrictOnDelete
            Schema::table('form_submissions', function (Blueprint $table) {
                $table->dropForeign('form_submissions_form_id_foreign');
                $table->foreign('form_id')->references('id')->on('forms')->restrictOnDelete();
            });
        }

        // Add index on login_attempts.ip_address
        Schema::table('login_attempts', function (Blueprint $table) {
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        // Remove index on login_attempts.ip_address
        Schema::table('login_attempts', function (Blueprint $table) {
            $table->dropIndex(['ip_address']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            // Restore form_submissions.form_id to cascadeOnDelete
            Schema::table('form_submissions', function (Blueprint $table) {
                $table->dropForeign('form_submissions_form_id_foreign');
                $table->foreign('form_id')->references('id')->on('forms')->cascadeOnDelete();
            });

            // Restore faqs.category_id to cascadeOnDelete
            Schema::table('faqs', function (Blueprint $table) {
                $table->dropForeign('faqs_category_id_foreign');
                $table->foreign('category_id')->references('id')->on('faq_categories')->cascadeOnDelete();
            });
        }

        // Remove FK + index on service_categories.hero_media_id
        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropForeign(['hero_media_id']);
            $table->dropIndex(['hero_media_id']);
        });

        // Remove FK + index on blog_posts.featured_image_id
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['featured_image_id']);
            $table->dropIndex(['featured_image_id']);
        });
    }
};
