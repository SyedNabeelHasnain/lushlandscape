<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$forms = \App\Models\Form::all();
foreach ($forms as $form) {
    echo "Form: " . $form->name . " | Slug: " . $form->slug . "\n";
}
