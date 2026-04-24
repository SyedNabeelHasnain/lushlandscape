<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Handle a dummy request to bootstrap the app completely
$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

$aliases = \App\Models\RouteAlias::where('is_active', true)->inRandomOrder()->take(50)->get();

foreach ($aliases as $alias) {
    $url = '/' . ltrim($alias->slug, '/');
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::create($url, 'GET')
    );
    echo str_pad($url, 50) . " => " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 500) {
        echo "Exception: " . $response->exception->getMessage() . "\n";
        echo "File: " . $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
    }
}
