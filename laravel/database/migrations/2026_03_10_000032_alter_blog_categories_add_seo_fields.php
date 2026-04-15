<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->string('short_description')->nullable()->after('description');
            $table->unsignedBigInteger('image_media_id')->nullable()->after('short_description');
            $table->string('og_title')->nullable()->after('image_media_id');
            $table->string('og_description')->nullable()->after('og_title');
            $table->string('schema_type', 100)->default('CollectionPage')->after('og_description');
            $table->json('schema_json')->nullable()->after('schema_type');
            $table->string('language', 10)->default('en')->after('schema_json');

            $table->foreign('image_media_id')
                ->references('id')->on('media_assets')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('blog_categories', function (Blueprint $table) {
            $table->dropForeign(['image_media_id']);
            $table->dropColumn([
                'short_description', 'image_media_id', 'og_title',
                'og_description', 'schema_type', 'schema_json', 'language',
            ]);
        });
    }
};
