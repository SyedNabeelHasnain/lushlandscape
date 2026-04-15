<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('theme_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->index(); // 'header', 'footer', 'single', 'archive'
            $table->boolean('is_active')->default(true);
            $table->json('conditions')->nullable(); // rules for where this applies
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_layouts');
    }
};
