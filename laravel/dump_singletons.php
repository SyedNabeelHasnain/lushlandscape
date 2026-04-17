<?php

use App\Models\SingletonPage;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$pages = SingletonPage::all();
foreach ($pages as $p) {
    echo 'Singleton: '.$p->slug."\n";
}
