<?php

use App\Models\PageBlock;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$blocks = PageBlock::forPage('service', 1)->orderBy('sort_order')->get();
foreach ($blocks as $block) {
    echo 'Block: '.$block->block_type."\n";
    $content = $block->content;
    if (isset($content['variant'])) {
        echo '  Variant: '.$content['variant']."\n";
    }
    if (isset($content['layout'])) {
        echo '  Layout: '.$content['layout']."\n";
    }
    if (isset($content['presentation_mode'])) {
        echo '  Presentation: '.$content['presentation_mode']."\n";
    }
    if (isset($content['form_slug'])) {
        echo '  Form Slug: '.$content['form_slug']."\n";
    }
    echo "\n";
}
