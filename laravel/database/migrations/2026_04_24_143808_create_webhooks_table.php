<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url', 2048);
            $table->string('event')->comment('The system event to listen to (e.g. entry.published, form.submitted)');
            $table->string('secret')->nullable()->comment('Optional signature secret for HMAC verification');
            $table->boolean('is_active')->default(true);
            $table->json('headers')->nullable()->comment('Custom headers to send with the payload');
            $table->integer('timeout')->default(5)->comment('Timeout in seconds');
            $table->integer('retry_count')->default(0)->comment('Number of times to retry on failure');
            $table->timestamps();
        });

        Schema::create('webhook_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('webhook_id')->constrained()->cascadeOnDelete();
            $table->string('event');
            $table->json('payload');
            $table->json('response_headers')->nullable();
            $table->text('response_body')->nullable();
            $table->integer('status_code')->nullable();
            $table->boolean('is_successful');
            $table->string('error_message')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_deliveries');
        Schema::dropIfExists('webhooks');
    }
};
