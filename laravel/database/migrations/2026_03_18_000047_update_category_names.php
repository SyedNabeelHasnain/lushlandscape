<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('service_categories')
            ->where('name', 'Structural Repair & Restoration')
            ->update([
                'name' => 'Structural Hardscape & Repair',
                'system_slug' => 'structural-hardscape-repair',
                'slug_final' => 'structural-hardscape-repair',
                'short_description' => 'Expert interlock restoration, sealing, repair, and retaining wall construction for lasting structural integrity.',
            ]);

        DB::table('service_categories')
            ->where('name', 'Softscaping & Lifestyle')
            ->update([
                'name' => 'Softscaping & Lifestyle Enhancements',
                'system_slug' => 'softscaping-lifestyle-enhancements',
                'slug_final' => 'softscaping-lifestyle-enhancements',
                'short_description' => 'Complete softscaping services including sod, turf, garden design, and landscape lighting to elevate your outdoor living.',
            ]);
    }

    public function down(): void
    {
        DB::table('service_categories')
            ->where('name', 'Structural Hardscape & Repair')
            ->update([
                'name' => 'Structural Repair & Restoration',
                'system_slug' => 'structural-repair-restoration',
                'slug_final' => 'structural-repair-restoration',
                'short_description' => 'Expert interlock restoration, sealing, repair, and retaining wall construction for lasting structural integrity.',
            ]);

        DB::table('service_categories')
            ->where('name', 'Softscaping & Lifestyle Enhancements')
            ->update([
                'name' => 'Softscaping & Lifestyle',
                'system_slug' => 'softscaping-lifestyle',
                'slug_final' => 'softscaping-lifestyle',
                'short_description' => 'Complete softscaping services including sod, turf, garden design, and landscape lighting.',
            ]);
    }
};
