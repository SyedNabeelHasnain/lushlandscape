<?php
// refactor_controllers.php
$dir = new RecursiveDirectoryIterator(__DIR__ . '/app/Http/Controllers/Frontend');
$iter = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($iter, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    $original = $content;

    // PortfolioProject
    $content = preg_replace(
        '/PortfolioProject::where/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'portfolio-project\'))->where',
        $content
    );
    $content = preg_replace(
        '/PortfolioProject::published\(\)/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'portfolio-project\'))->where(\'status\', \'published\')',
        $content
    );

    // City
    $content = preg_replace(
        '/City::where/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'city\'))->where',
        $content
    );
    $content = preg_replace(
        '/City::published\(\)/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'city\'))->where(\'status\', \'published\')',
        $content
    );

    // Service
    $content = preg_replace(
        '/Service::where/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'service\'))->where',
        $content
    );
    $content = preg_replace(
        '/Service::published\(\)/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'service\'))->where(\'status\', \'published\')',
        $content
    );

    // BlogPost
    $content = preg_replace(
        '/BlogPost::where/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'blog-post\'))->where',
        $content
    );
    $content = preg_replace(
        '/BlogPost::published\(\)/i',
        '\\App\\Models\\Entry::whereHas(\'contentType\', fn($q) => $q->where(\'slug\', \'blog-post\'))->where(\'status\', \'published\')',
        $content
    );
    
    // ServiceCategory
    $content = preg_replace(
        '/ServiceCategory::where/i',
        '\\App\\Models\\Term::whereHas(\'taxonomy\', fn($q) => $q->where(\'slug\', \'service-categories\'))->where',
        $content
    );

    // Replace slug_final with slug
    $content = str_replace('slug_final', 'slug', $content);

    // Property access replacements
    $content = preg_replace('/->name/i', '->title', $content);
    $content = preg_replace('/->description/i', '->data[\'description\']', $content);
    $content = preg_replace('/->body/i', '->data[\'body\']', $content);
    $content = preg_replace('/->short_description/i', '->description', $content);

    if ($original !== $content) {
        file_put_contents($path, $content);
        echo "Updated: $path\n";
    }
}
