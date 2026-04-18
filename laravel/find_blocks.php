<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/app/Http/Controllers/Frontend'));
foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (strpos($content, 'BlockBuilderService::getBlocks') !== false) {
            echo "File: " . $file->getFilename() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                if (strpos($line, 'BlockBuilderService::getBlocks') !== false) {
                    echo "  Line " . ($i + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
