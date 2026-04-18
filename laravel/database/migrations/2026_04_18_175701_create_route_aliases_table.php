<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('route_aliases', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // The actual URL path (e.g. 'landscaping-toronto')
            $table->morphs('routable'); // Polymorphic link to Entry, Term, or any custom model
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_aliases');
    }
};
