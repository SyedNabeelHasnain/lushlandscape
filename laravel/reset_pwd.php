<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

$user = \App\Models\User::first();
$user->password = \Illuminate\Support\Facades\Hash::make('password123');
$user->save();
echo "Password reset for: " . $user->email . "\n";
