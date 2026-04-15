<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->string('email_subject')->nullable()->after('success_message');
            $table->text('confirmation_message')->nullable()->after('email_subject');
            $table->string('admin_badge')->nullable()->after('confirmation_message');
        });
    }

    public function down(): void
    {
        Schema::table('forms', function (Blueprint $table) {
            $table->dropColumn(['email_subject', 'confirmation_message', 'admin_badge']);
        });
    }
};
