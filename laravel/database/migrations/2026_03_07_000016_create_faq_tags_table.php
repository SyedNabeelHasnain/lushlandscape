<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('faq_tag_map', function (Blueprint $table) {
            $table->foreignId('faq_id')->constrained('faqs')->cascadeOnDelete();
            $table->foreignId('faq_tag_id')->constrained('faq_tags')->cascadeOnDelete();
            $table->primary(['faq_id', 'faq_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_tag_map');
        Schema::dropIfExists('faq_tags');
    }
};
