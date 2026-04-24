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
        Schema::table('media_assets', function (Blueprint $table) {
            $indexes = Schema::getIndexes('media_assets');
            $indexNames = array_column($indexes, 'name');

            if (in_array('media_assets_checksum_idx', $indexNames)) {
                $table->dropIndex('media_assets_checksum_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_assets', function (Blueprint $table) {
            $indexes = Schema::getIndexes('media_assets');
            $indexNames = array_column($indexes, 'name');

            if (! in_array('media_assets_checksum_idx', $indexNames)) {
                $table->index('checksum', 'media_assets_checksum_idx');
            }
        });
    }
};
