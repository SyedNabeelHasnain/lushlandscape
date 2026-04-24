<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('page_content_blocks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down migration since this is a cleanup
    }
};
