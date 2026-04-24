<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    // Tables available for export with their display labels
    private static array $exportable = [
        'service_categories' => 'Service Categories',
        'services' => 'Services',
        'cities' => 'Cities',
        'neighborhoods' => 'Neighborhoods',
        'service_city_pages' => 'Service-City Pages',
        'faqs' => 'FAQs',
        'faq_categories' => 'FAQ Categories',
        'faq_assignments' => 'FAQ Assignments',
        'media_assets' => 'Media Assets',
        'blog_posts' => 'Blog Posts',
        'blog_categories' => 'Blog Categories',
        'reviews' => 'Reviews',
        'review_categories' => 'Review Categories',
        'portfolio_projects' => 'Portfolio Projects',
        'portfolio_categories' => 'Portfolio Categories',
        'settings' => 'Settings',
        'static_pages' => 'Static Pages',
        'forms' => 'Forms',
        'form_fields' => 'Form Fields',
        'form_submissions' => 'Form Submissions',
        'popups' => 'Popups',
        'redirects' => 'Redirects',
        'security_rules' => 'Security Rules',
        'page_blocks' => 'Page Builder Blocks',
    ];

    // Columns to exclude from export (sensitive data)
    private static array $excludedColumns = [
        'users' => ['password', 'remember_token'],
        'settings' => [], // openai_api_key handled separately
    ];

    public static function getExportableTables(): array
    {
        return self::$exportable;
    }

    public static function export(string $table): StreamedResponse
    {
        if (! array_key_exists($table, self::$exportable)) {
            abort(422, 'Table not exportable.');
        }

        }

        $filename = $table.'_'.date('Y-m-d_His').'.csv';

        return new StreamedResponse(function () use ($table) {
            $handle = fopen('php://output', 'w');

            $columns = DB::getSchemaBuilder()->getColumnListing($table);
            $excluded = self::$excludedColumns[$table] ?? [];

            // Filter sensitive columns for settings
            if ($table === 'settings') {
                // Exclude rows where key is openai_api_key
            }

            $columns = array_diff($columns, $excluded);
            $columns = array_values($columns);

            // Header row
            fputcsv($handle, $columns);

            // Data rows (chunked for memory efficiency)
            $query = DB::table($table)->select($columns);

            // For settings, exclude the openai_api_key row entirely for security
            if ($table === 'settings') {
                $query->where('key', '!=', 'openai_api_key')
                    ->orderBy('group')->orderBy('sort_order');
            }

            $query->orderBy('id')->chunk(500, function ($rows) use ($handle, $columns) {
                foreach ($rows as $row) {
                    $rowArray = (array) $row;

                    $values = [];
                    foreach ($columns as $col) {
                        $values[] = $rowArray[$col] ?? '';
                    }
                    fputcsv($handle, $values);
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }
}
