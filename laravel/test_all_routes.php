<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$urls = [
    '/',
    '/locations',
    '/services',
    '/portfolio',
    '/blog',
    '/contact',
    '/consultation',
    '/search',
    '/sitemap.xml',
    '/llms.txt',
    '/llms-full.txt'
];

foreach ($urls as $url) {
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::create($url, 'GET')
    );
    echo str_pad($url, 20) . " => " . $response->getStatusCode() . "\n";
    if ($response->getStatusCode() >= 500) {
        echo "Exception: " . $response->exception->getMessage() . "\n";
        echo "File: " . $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
    }
}
