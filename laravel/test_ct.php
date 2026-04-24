<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::create('/', 'GET'));

$ct = \App\Models\ContentType::first();
echo $ct ? $ct->toJson(JSON_PRETTY_PRINT) : "No content types found";
