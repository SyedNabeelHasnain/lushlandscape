<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('entries')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('status')->default('draft'); // published, draft, archived
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->json('data')->nullable(); // Hybrid EAV Engine
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['content_type_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
