<?php

use App\Models\Form;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$forms = Form::all();
foreach ($forms as $form) {
    echo "Form: {$form->name}\n";
    echo "  Requires OTP: {$form->requires_email_verification}\n";
    echo "  Email To: {$form->email_to}\n";
    echo "  Success Msg: {$form->success_message}\n";
}
