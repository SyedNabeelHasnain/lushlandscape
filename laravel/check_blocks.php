<?php

use App\Models\PageBlock;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$blocks = PageBlock::all();
file_put_contents('blocks_output.txt', 'Total blocks: '.$blocks->count()."\n");

$homeBlocks = PageBlock::where('page_type', 'home')->get();
file_put_contents('blocks_output.txt', 'Home blocks count: '.$homeBlocks->count()."\n", FILE_APPEND);
foreach ($homeBlocks as $b) {
    file_put_contents('blocks_output.txt', "ID: {$b->id}, Type: {$b->block_type}, Page ID: ".var_export($b->page_id, true).", Enabled: {$b->is_enabled}\n", FILE_APPEND);
}

// Let's also check ServicesHubSeeder, AboutPageSeeder
$aboutBlocks = PageBlock::where('page_type', 'static_page')->get();
file_put_contents('blocks_output.txt', 'About blocks count: '.$aboutBlocks->count()."\n", FILE_APPEND);
foreach ($aboutBlocks as $b) {
    file_put_contents('blocks_output.txt', "ID: {$b->id}, Type: {$b->block_type}, Page ID: ".var_export($b->page_id, true).", Enabled: {$b->is_enabled}\n", FILE_APPEND);
}
