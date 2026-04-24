<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::create('/llms-full.txt', 'GET')
);

echo 'Status: '.$response->getStatusCode()."\n";
if ($response->getStatusCode() >= 500) {
    echo 'Exception: '.$response->exception->getMessage()."\n";
    echo 'File: '.$response->exception->getFile().':'.$response->exception->getLine()."\n";
}
