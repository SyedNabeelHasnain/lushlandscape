<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

try {
    $count = \App\Models\User::count();
    echo "Users in DB: $count\n";
    $admin = \App\Models\User::first();
    if ($admin) {
        echo "First user: {$admin->email}\n";
    } else {
        echo "NO USERS FOUND!\n";
    }
} catch (\Exception $e) {
    echo "Error querying users: " . $e->getMessage() . "\n";
}
