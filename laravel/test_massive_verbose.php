<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

$aliases = \App\Models\RouteAlias::where('is_active', true)->get();
$errors = 0;
$tested = 0;

echo "Testing " . $aliases->count() . " active URLs...\n";

foreach ($aliases as $alias) {
    $url = '/' . ltrim($alias->slug, '/');
    echo "Testing: " . $url . "... ";
    
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::create($url, 'GET')
    );
    
    echo $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 500) {
        echo "  Exception: " . $response->exception->getMessage() . "\n";
        echo "  File: " . $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
        $errors++;
    }
    $tested++;
}

echo "\nTested: $tested\nErrors: $errors\n";
