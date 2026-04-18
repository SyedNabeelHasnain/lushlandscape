<?php
$dir = new RecursiveDirectoryIterator(__DIR__.'/resources/views');
$iterator = new RecursiveIteratorIterator($dir);
foreach ($iterator as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        if (preg_match('/fa-(solid|regular|brands|twitter|instagram|facebook|linkedin|youtube|arrow|chevron|times|bars|phone|envelope|map-marker)/', $content)) {
            echo $file->getPathname() . "\n";
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                if (preg_match('/fa-(solid|regular|brands|twitter|instagram|facebook|linkedin|youtube|arrow|chevron|times|bars|phone|envelope|map-marker)/', $line)) {
                    echo "  Line " . ($i + 1) . ": " . trim($line) . "\n";
                }
            }
        }
    }
}
