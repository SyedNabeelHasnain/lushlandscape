<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$dirs = ['app/Http/Controllers/Frontend'];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/app/Http/Controllers/Frontend')) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        preg_match_all("/BlockBuilderService::getBlocks\('([^']+)',/i", $content, $matches);
        if (! empty($matches[1])) {
            echo $file->getFilename().': '.implode(', ', array_unique($matches[1]))."\n";
        }
    }
}
