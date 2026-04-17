<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pages = \App\Models\SingletonPage::all();
foreach ($pages as $p) {
    echo "Singleton: " . $p->slug . "\n";
}
