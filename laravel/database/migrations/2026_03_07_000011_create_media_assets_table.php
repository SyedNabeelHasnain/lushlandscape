<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('internal_title');
            $table->string('canonical_filename');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('media_type');
            $table->string('editorial_class')->nullable();
            $table->string('mime_type');
            $table->string('extension');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('aspect_ratio')->nullable();
            $table->string('orientation')->nullable();
            $table->unsignedInteger('duration')->nullable();
            $table->text('description')->nullable();
            $table->string('default_alt_text')->nullable();
            $table->text('default_caption')->nullable();
            $table->string('credit')->nullable();
            $table->string('language')->default('en');
            $table->string('image_purpose')->default('informative');
            $table->json('focal_point')->nullable();
            $table->boolean('text_present')->default(false);
            $table->boolean('social_preview_eligible')->default(false);
            $table->boolean('schema_eligible')->default(false);
            $table->string('location_city')->nullable();
            $table->string('location_region')->nullable();
            $table->string('checksum')->nullable();
            $table->json('tags')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets');
    }
};
