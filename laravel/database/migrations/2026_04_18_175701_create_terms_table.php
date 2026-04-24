<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taxonomy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('terms')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->json('data')->nullable(); // For custom fields on terms (e.g. icon, color)
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['taxonomy_id', 'slug']);
        });

        // Polymorphic pivot table to link Terms to Entries (or anything else)
        Schema::create('termables', function (Blueprint $table) {
            $table->foreignId('term_id')->constrained()->cascadeOnDelete();
            $table->morphs('termable');
            $table->timestamps();

            $table->unique(['term_id', 'termable_id', 'termable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('termables');
        Schema::dropIfExists('terms');
    }
};
