<?php

$logPath = __DIR__.'/storage/logs/laravel.log';
if (file_exists($logPath)) {
    $lines = file($logPath);
    $lastLines = array_slice($lines, -100);
    file_put_contents(__DIR__.'/last_errors.txt', implode('', $lastLines));
} else {
    file_put_contents(__DIR__.'/last_errors.txt', 'No log file found.');
}
