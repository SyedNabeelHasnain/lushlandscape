<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$request = Request::create('/llms-full.txt');
$response = $kernel->handle($request);
echo 'Status: '.$response->getStatusCode()."\n";
if ($response->getStatusCode() >= 500) {
    echo $response->getContent();
}
