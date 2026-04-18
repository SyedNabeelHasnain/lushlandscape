<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
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
    'MiscPagesSeeder'
];

foreach ($seeders as $class) {
    echo "Running $class...\n";
    Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => $class,
        '--force' => true
    ]);
    echo Illuminate\Support\Facades\Artisan::output() . "\n";
}

Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "Cache cleared.\n";
Illuminate\Support\Facades\Artisan::call('view:clear');
echo "Views cleared.\n";
