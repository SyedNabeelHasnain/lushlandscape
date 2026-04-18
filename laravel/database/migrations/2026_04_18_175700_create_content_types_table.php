<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('layout_template')->default('default');
            $table->boolean('is_hierarchical')->default(false);
            $table->boolean('has_archives')->default(true);
            $table->json('schema_json')->nullable(); // For defining expected fields/types
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_types');
    }
};
