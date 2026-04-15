<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_content_blocks', function (Blueprint $table) {
            $table->id();
            // polymorphic owner: page_type + page_id (e.g. 'static_page' + 5)
            $table->string('page_type', 60);
            $table->unsignedBigInteger('page_id');
            // optional: which page section this block lives in (null = standalone)
            $table->string('section_key', 60)->nullable();
            // block identity
            $table->string('block_type', 60); // heading, paragraph, two_column, cards_grid, etc.
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            // all block content stored as JSON
            $table->json('content')->nullable();
            $table->timestamps();

            $table->index(['page_type', 'page_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_content_blocks');
    }
};
