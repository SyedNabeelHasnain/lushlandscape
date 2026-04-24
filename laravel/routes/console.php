<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;

Schedule::command('sitemap:generate')->daily();

Artisan::command('admin:reset-password {password}', function ($password) {
    $user = User::where('email', 'admin@lushlandscape.ca')->first();
    if (!$user) {
        $this->error('Admin user not found.');
        return;
    }
    $user->password = Hash::make($password);
    $user->save();
    $this->info('Admin password reset successfully.');
})->purpose('Reset the admin password directly from the CLI');
