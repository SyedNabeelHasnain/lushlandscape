<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

$user = \App\Models\User::first();
echo "User: " . $user->email . "\n";

try {
    \App\Models\LoginAttempt::create([
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'status' => 'success',
        'user_agent' => 'test',
    ]);
    echo "LoginAttempt create success.\n";
} catch (\Exception $e) {
    echo "LoginAttempt create error: " . $e->getMessage() . "\n";
}

try {
    $user->update(['last_login_at' => now()]);
    echo "User update success.\n";
} catch (\Exception $e) {
    echo "User update error: " . $e->getMessage() . "\n";
}
