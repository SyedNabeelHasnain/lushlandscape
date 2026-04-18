<?php
$iteratorJs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/app'));
foreach ($iteratorJs as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.php') !== false) {
        $content = file_get_contents($file->getPathname());
        if (stripos($content, 'Mail::') !== false || stripos($content, 'Notification::') !== false) {
            echo "File: " . $file->getPathname() . "\n";
        }
    }
}
