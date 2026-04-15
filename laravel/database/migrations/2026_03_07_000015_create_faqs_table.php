<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('faq_categories')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->text('question');
            $table->text('short_answer')->nullable();
            $table->longText('answer');
            $table->string('answer_format')->default('html');
            $table->string('faq_type')->default('general');
            $table->string('audience_type')->default('customer');
            $table->string('language')->default('en');
            $table->string('seo_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->text('chatbot_summary')->nullable();
            $table->json('alternate_phrasings')->nullable();
            $table->json('semantic_keywords')->nullable();
            $table->string('search_intent_type')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('local_relevance')->default(false);
            $table->string('city_relevance')->nullable();
            $table->string('region_relevance')->nullable();
            $table->boolean('schema_eligible')->default(true);
            $table->unsignedInteger('helpful_count')->default(0);
            $table->unsignedInteger('not_helpful_count')->default(0);
            $table->string('status')->default('draft');
            $table->integer('display_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('review_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
