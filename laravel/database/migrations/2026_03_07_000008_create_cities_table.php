<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('province_name')->default('Ontario');
            $table->string('region_name')->nullable();
            $table->string('name');
            $table->string('system_slug');
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->unique();
            $table->string('navigation_label');
            $table->text('city_summary')->nullable();
            $table->json('city_body')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('local_conditions_json')->nullable();
            $table->json('municipal_notes_json')->nullable();
            $table->string('default_meta_title')->nullable();
            $table->string('default_meta_description')->nullable();
            $table->string('default_og_title')->nullable();
            $table->string('default_og_description')->nullable();
            $table->json('default_schema_json')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
