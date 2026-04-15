<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->json('keywords_json')->nullable()->after('schema_json');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->json('keywords_json')->nullable()->after('default_schema_json');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->json('keywords_json')->nullable()->after('default_schema_json');
        });

        Schema::table('service_city_pages', function (Blueprint $table) {
            $table->json('keywords_json')->nullable()->after('schema_json');
        });
    }

    public function down(): void
    {
        Schema::table('service_categories', fn (Blueprint $table) => $table->dropColumn('keywords_json'));
        Schema::table('services', fn (Blueprint $table) => $table->dropColumn('keywords_json'));
        Schema::table('cities', fn (Blueprint $table) => $table->dropColumn('keywords_json'));
        Schema::table('service_city_pages', fn (Blueprint $table) => $table->dropColumn('keywords_json'));
    }
};
