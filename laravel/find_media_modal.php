<?php

$iteratorJs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/resources/views/admin'));
foreach ($iteratorJs as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        if (stripos($content, 'media') !== false && stripos($content, 'modal') !== false) {
            echo 'File Blade: '.$file->getPathname()."\n";
        }
    }
}
$iteratorJs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/resources/js'));
foreach ($iteratorJs as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.js') !== false) {
        $content = file_get_contents($file->getPathname());
        if (stripos($content, 'media') !== false && stripos($content, 'modal') !== false) {
            echo 'File JS: '.$file->getPathname()."\n";
        }
    }
}
