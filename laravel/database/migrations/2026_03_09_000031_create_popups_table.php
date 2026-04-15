<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('popups', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                  // internal label
            $table->string('status')->default('draft');              // draft|active|archived
            $table->string('heading')->nullable();
            $table->text('body_content')->nullable();
            $table->foreignId('image_media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('form_id')->nullable()->constrained('forms')->nullOnDelete();
            $table->string('trigger_type')->default('delay');        // delay|scroll_percent|exit_intent
            $table->unsignedSmallInteger('trigger_delay_seconds')->default(5);
            $table->unsignedSmallInteger('trigger_scroll_percent')->default(50);
            $table->unsignedSmallInteger('suppress_days')->default(7);
            $table->boolean('show_on_mobile')->default(true);
            $table->boolean('show_to_returning')->default(false);
            $table->json('excluded_pages')->nullable();              // array of URL path prefixes
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popups');
    }
};
