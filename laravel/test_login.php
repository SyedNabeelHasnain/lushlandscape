<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$urls = ['/login', '/admin'];
foreach ($urls as $url) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    $response = $kernel->handle($request);
    echo "GET $url => " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 500) {
        echo "  Exception: " . $response->exception->getMessage() . "\n";
        echo "  File: " . $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
    }
}
