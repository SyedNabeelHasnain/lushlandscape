<?php
$lines = file(__DIR__.'/config/blocks.php');
foreach ($lines as $i => $line) {
    if (strpos($line, 'data_source') !== false && strpos($line, 'null') === false && strpos($line, '[') === false) {
        echo "Line " . ($i + 1) . ": " . trim($line) . "\n";
    }
}
