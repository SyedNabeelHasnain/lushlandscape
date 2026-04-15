<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->string('placeable_type');
            $table->unsignedBigInteger('placeable_id');
            $table->string('slot')->default('default');
            $table->string('alt_override')->nullable();
            $table->text('caption_override')->nullable();
            $table->text('description_override')->nullable();
            $table->boolean('is_decorative')->default(false);
            $table->json('crop_data')->nullable();
            $table->string('loading')->default('lazy');
            $table->string('fetchpriority')->nullable();
            $table->boolean('schema_eligible')->default(false);
            $table->boolean('social_preview')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['placeable_type', 'placeable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_placements');
    }
};
