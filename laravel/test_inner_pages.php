<?php

use App\Models\RouteAlias;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);

// Handle a dummy request to bootstrap the app completely
$kernel->handle(Request::create('/', 'GET'));

$aliases = RouteAlias::where('is_active', true)->inRandomOrder()->take(50)->get();

foreach ($aliases as $alias) {
    $url = '/'.ltrim($alias->slug, '/');
    $response = $kernel->handle(
        $request = Request::create($url, 'GET')
    );
    echo str_pad($url, 50).' => '.$response->getStatusCode()."\n";
    if ($response->getStatusCode() >= 500) {
        echo 'Exception: '.$response->exception->getMessage()."\n";
        echo 'File: '.$response->exception->getFile().':'.$response->exception->getLine()."\n";
    }
}
