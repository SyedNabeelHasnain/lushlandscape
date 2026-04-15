<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_city_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('neighborhood_id')->nullable()->constrained('neighborhoods')->nullOnDelete();
            $table->string('system_slug');
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->unique();
            $table->string('navigation_label');
            $table->string('page_title');
            $table->string('h1');
            $table->text('local_intro')->nullable();
            $table->json('content_json')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->json('schema_json')->nullable();
            $table->json('cta_json')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_indexable')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['service_id', 'city_id', 'neighborhood_id'], 'scp_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_city_pages');
    }
};
