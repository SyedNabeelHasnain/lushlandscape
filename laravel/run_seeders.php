<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$seeders = [
    'HomePageContentSeeder',
    'AboutPageSeeder',
    'ServicesHubSeeder',
    'LocationsHubSeeder',
    'PortfolioPageSeeder',
    'ConsultationPageSeeder',
    'SeoMatrixPagesSeeder',
    'ServicePagesSeeder',
    'MiscPagesSeeder',
];

foreach ($seeders as $class) {
    echo "Running $class...\n";
    Artisan::call('db:seed', [
        '--class' => $class,
        '--force' => true,
    ]);
    echo Artisan::output()."\n";
}

Artisan::call('cache:clear');
echo "Cache cleared.\n";
Artisan::call('view:clear');
echo "Views cleared.\n";
