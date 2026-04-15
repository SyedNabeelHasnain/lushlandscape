<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('portfolio_categories')
                ->nullOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();

            $table->unsignedBigInteger('image_media_id')->nullable();
            $table->foreign('image_media_id')
                ->references('id')->on('media_assets')
                ->nullOnDelete();

            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('schema_type', 100)->default('ItemList');
            $table->json('schema_json')->nullable();

            $table->string('status')->default('published');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_categories');
    }
};
