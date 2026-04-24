<?php

$lines = file(__DIR__.'/resources/js/app.js');
foreach ($lines as $i => $line) {
    if (strpos($line, 'createIcons') !== false) {
        echo 'Line '.($i + 1).': '.trim($line)."\n";
    }
}
