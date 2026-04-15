<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lushlandscape.ca'],
            [
                'name' => 'Admin',
                'password' => Hash::make(config('auth.admin_seed_password') ?? throw new \RuntimeException('auth.admin_seed_password config not set.')),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}
