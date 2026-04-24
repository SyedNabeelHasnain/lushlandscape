<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Mail;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

try {
    Mail::raw('Test email', function ($message) {
        $message->to('syednabeelhasnain@gmail.com')
            ->subject('Test Email from Lush');
    });
    echo "Mail sent successfully.\n";
} catch (Exception $e) {
    echo 'Error sending mail: '.$e->getMessage()."\n";
    echo $e->getTraceAsString()."\n";
}
