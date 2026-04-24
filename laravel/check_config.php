<?php

use Illuminate\Contracts\Http\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);

$types = config('blocks.types');
foreach ($types as $key => $val) {
    if (! is_array($val)) {
        echo "Type $key is ".gettype($val).': '.var_export($val, true)."\n";
    }
}
