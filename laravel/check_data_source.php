<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$types = config('blocks.types');
foreach ($types as $key => $val) {
    if (isset($val['data_source']) && !is_array($val['data_source']) && $val['data_source'] !== null) {
        echo "Type '$key' has data_source of type " . gettype($val['data_source']) . ": " . var_export($val['data_source'], true) . "\n";
    }
}
