<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('service_categories')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('name');
            $table->string('system_slug');
            $table->string('custom_slug')->nullable();
            $table->string('slug_final')->unique();
            $table->string('navigation_label');
            $table->string('service_code')->nullable();
            $table->text('service_summary')->nullable();
            $table->json('service_body')->nullable();
            $table->string('default_meta_title')->nullable();
            $table->string('default_meta_description')->nullable();
            $table->string('default_og_title')->nullable();
            $table->string('default_og_description')->nullable();
            $table->json('default_schema_json')->nullable();
            $table->string('icon')->nullable();
            $table->string('status')->default('draft');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
