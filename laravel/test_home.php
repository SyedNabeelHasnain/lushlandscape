<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routes = ['/', '/services', '/portfolio', '/about-us', '/locations', '/contact', '/consultation'];

foreach ($routes as $route) {
    try {
        $request = Illuminate\Http\Request::create($route, 'GET');
        $response = $kernel->handle($request);
        
        echo "Route $route -> Status: " . $response->getStatusCode() . "\n";
        if ($response->getStatusCode() >= 400 && isset($response->exception)) {
            echo "Exception: " . $response->exception->getMessage() . "\n";
            echo "File: " . $response->exception->getFile() . " on line " . $response->exception->getLine() . "\n";
        }
    } catch (\Throwable $e) {
        echo "Route $route -> Exception: " . $e->getMessage() . "\n";
    }
}
