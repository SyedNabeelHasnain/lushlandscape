<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page_type', 50);      // 'city', 'service_city_page', 'static_page'
            $table->unsignedBigInteger('page_id')->nullable(); // null = template default for page_type
            $table->string('section_key', 100);
            $table->boolean('is_enabled')->default(true);
            $table->boolean('show_on_desktop')->default(true);
            $table->boolean('show_on_mobile')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->unique(['page_type', 'page_id', 'section_key'], 'page_sections_unique');
            $table->index(['page_type', 'page_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
