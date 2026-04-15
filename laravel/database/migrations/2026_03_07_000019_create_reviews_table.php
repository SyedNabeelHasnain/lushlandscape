<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('reviewer_name');
            $table->string('reviewer_initial')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('source')->default('direct');
            $table->string('source_url')->nullable();
            $table->string('city_relevance')->nullable();
            $table->string('neighborhood_mention')->nullable();
            $table->string('service_relevance')->nullable();
            $table->date('review_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('review_assignments', function (Blueprint $table) {
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->string('assignable_type');
            $table->unsignedBigInteger('assignable_id');
            $table->integer('sort_order')->default(0);
            $table->primary(['review_id', 'assignable_type', 'assignable_id'], 'review_assign_pk');
            $table->index(['assignable_type', 'assignable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_assignments');
        Schema::dropIfExists('reviews');
    }
};
