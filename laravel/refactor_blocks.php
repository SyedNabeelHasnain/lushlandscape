<?php
// refactor_blocks.php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$iter = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($iter, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;

    // Replace PortfolioProject queries
    $content = preg_replace(
        '/\\\\App\\\\Models\\\\PortfolioProject::where/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'portfolio-project\'))->where',
        $content
    );

    // Replace City queries
    $content = preg_replace(
        '/\\\\App\\\\Models\\\\City::where/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'city\'))->where',
        $content
    );

    // Replace Service queries
    $content = preg_replace(
        '/\\\\App\\\\Models\\\\Service::where/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'service\'))->where',
        $content
    );

    // Replace slug_final with slug
    $content = str_replace('slug_final', 'slug', $content);

    // Replace App\Models\City etc if they are just imported
    $content = preg_replace('/use App\\\\Models\\\\(City|Service|PortfolioProject|BlogPost);/', 'use App\Models\Entry;', $content);

    if ($original !== $content) {
        file_put_contents($path, $content);
        echo "Updated: $path\n";
    }
}
