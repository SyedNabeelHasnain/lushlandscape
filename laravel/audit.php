<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$issues = [];

// Check models for missing $fillable or $guarded
$modelsPath = app_path('Models');
$models = glob($modelsPath . '/*.php');
foreach ($models as $model) {
    $content = file_get_contents($model);
    if (strpos($content, 'extends Model') !== false) {
        if (strpos($content, '$fillable') === false && strpos($content, '$guarded') === false) {
            $issues['security'][] = "Model " . basename($model) . " is missing \$fillable or \$guarded protection.";
        }
    }
}

// Check controllers for N+1 queries (basic heuristic)
$controllersPath = app_path('Http/Controllers');
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllersPath));
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        if (preg_match_all('/([A-Z][a-zA-Z0-9_]*)::(all|get|paginate)\(\)/', $content, $matches)) {
            foreach ($matches[1] as $model) {
                // Heuristic: Using all/get without with() might be N+1
                // This is a weak heuristic, but we can look closer.
                $issues['performance'][] = "Potential N+1 query in " . $file->getFilename() . " via $model::" . $matches[2][0] . "() without with() explicit locally. Needs manual check.";
            }
        }
    }
}

// Output
echo json_encode($issues, JSON_PRETTY_PRINT);
