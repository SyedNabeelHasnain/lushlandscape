<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faq_id')->constrained('faqs')->cascadeOnDelete();
            $table->string('assignable_type');
            $table->unsignedBigInteger('assignable_id');
            $table->string('local_title_override')->nullable();
            $table->integer('local_display_order')->default(0);
            $table->boolean('is_collapsed')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['assignable_type', 'assignable_id']);
            $table->unique(['faq_id', 'assignable_type', 'assignable_id'], 'faq_assign_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_assignments');
    }
};
