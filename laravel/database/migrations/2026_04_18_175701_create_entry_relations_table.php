<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entry_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_entry_id')->constrained('entries')->cascadeOnDelete();
            $table->foreignId('target_entry_id')->constrained('entries')->cascadeOnDelete();
            $table->string('relation_type')->default('related'); // To support multiple types of relations (e.g., 'matrix', 'cross_sell')
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['source_entry_id', 'relation_type']);
            $table->unique(['source_entry_id', 'target_entry_id', 'relation_type'], 'entry_relation_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_relations');
    }
};
