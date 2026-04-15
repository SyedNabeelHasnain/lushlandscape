<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportService
{
    // Unique key columns used for updateOrCreate matching per table
    private static array $uniqueKeys = [
        'service_categories' => ['id'],
        'services' => ['id'],
        'cities' => ['id'],
        'neighborhoods' => ['id'],
        'service_city_pages' => ['id'],
        'faqs' => ['id'],
        'faq_categories' => ['id'],
        'faq_assignments' => ['id'],
        'media_assets' => ['id'],
        'blog_posts' => ['id'],
        'blog_categories' => ['id'],
        'reviews' => ['id'],
        'review_categories' => ['id'],
        'portfolio_projects' => ['id'],
        'portfolio_categories' => ['id'],
        'settings' => ['key'],
        'static_pages' => ['id'],
        'forms' => ['id'],
        'form_fields' => ['id'],
        'form_submissions' => ['id'],
        'popups' => ['id'],
        'redirects' => ['id'],
        'security_rules' => ['id'],
        'page_blocks' => ['id'],
        'page_content_blocks' => ['id'],
        'page_sections' => ['id'],
    ];

    // Columns that should not be imported (auto-managed)
    private static array $skipColumns = ['created_at', 'updated_at'];

    /**
     * Parse CSV and return a preview without modifying the database.
     */
    public static function dryRun(string $table, UploadedFile $file): array
    {
        if (in_array($table, ['page_content_blocks', 'page_sections'], true)) {
            LegacyGovernanceService::legacyRead('legacy_import_dry_run', 'table_import', $table);
        }

        $rows = self::parseCsv($file);
        if (empty($rows)) {
            return ['success' => false, 'error' => 'CSV file is empty or could not be parsed.'];
        }

        $headers = array_keys($rows[0]);
        $dbColumns = DB::getSchemaBuilder()->getColumnListing($table);
        $uniqueCols = self::$uniqueKeys[$table] ?? ['id'];

        // Validate headers match DB columns
        $invalidHeaders = array_diff($headers, $dbColumns);
        $missingRequired = array_diff($uniqueCols, $headers);

        if (! empty($missingRequired)) {
            return [
                'success' => false,
                'error' => 'Missing required columns: '.implode(', ', $missingRequired),
            ];
        }

        $creates = [];
        $updates = [];
        $skips = [];
        $errors = [];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2; // +2 for 1-indexed + header row

            // Build match criteria
            $match = [];
            foreach ($uniqueCols as $col) {
                $match[$col] = $row[$col] ?? null;
            }

            // Check if record exists
            $existing = DB::table($table)->where($match)->first();

            if ($existing) {
                $updates[] = ['row' => $rowNum, 'match' => $match, 'data' => $row];
            } else {
                $creates[] = ['row' => $rowNum, 'match' => $match, 'data' => $row];
            }
        }

        return [
            'success' => true,
            'headers' => $headers,
            'invalid_headers' => array_values($invalidHeaders),
            'total' => count($rows),
            'creates' => count($creates),
            'updates' => count($updates),
            'skips' => count($skips),
            'errors' => $errors,
            'preview' => array_slice($rows, 0, 10),
        ];
    }

    /**
     * Import CSV data into the database.
     */
    public static function import(string $table, UploadedFile $file): array
    {
        if (in_array($table, ['page_content_blocks', 'page_sections'], true)) {
            LegacyGovernanceService::legacyRead('legacy_import', 'table_import', $table);
        }

        $rows = self::parseCsv($file);
        if (empty($rows)) {
            return ['success' => false, 'error' => 'CSV file is empty.'];
        }

        $dbColumns = DB::getSchemaBuilder()->getColumnListing($table);
        $uniqueCols = self::$uniqueKeys[$table] ?? ['id'];
        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $i => $row) {
                $rowNum = $i + 2;

                // Filter to valid DB columns only, skip auto-managed
                $data = [];
                foreach ($row as $col => $val) {
                    if (in_array($col, $dbColumns) && ! in_array($col, self::$skipColumns)) {
                        $data[$col] = $val === '' ? null : $val;
                    }
                }

                // Build match criteria
                $match = [];
                foreach ($uniqueCols as $col) {
                    if (isset($data[$col])) {
                        $match[$col] = $data[$col];
                    }
                }

                if (empty($match)) {
                    $errors[] = "Row {$rowNum}: Missing unique key columns.";

                    continue;
                }

                // Remove match keys from data to avoid duplicate assignment
                $updateData = array_diff_key($data, $match);

                try {
                    $existing = DB::table($table)->where($match)->first();
                    if ($existing) {
                        DB::table($table)->where($match)->update($updateData);
                        $updated++;
                    } else {
                        DB::table($table)->insert($data);
                        $created++;
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Row {$rowNum}: ".$e->getMessage();
                }
            }

            if (! empty($errors) && count($errors) > count($rows) / 2) {
                DB::rollBack();

                return ['success' => false, 'error' => 'Too many errors. Import rolled back.', 'errors' => $errors];
            }

            DB::commit();

            return [
                'success' => true,
                'created' => $created,
                'updated' => $updated,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed', ['table' => $table, 'error' => $e->getMessage()]);

            return ['success' => false, 'error' => 'Import failed: '.$e->getMessage()];
        }
    }

    /**
     * Parse a CSV file into an array of associative arrays.
     */
    private static function parseCsv(UploadedFile $file): array
    {
        $rows = [];
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return [];
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);

            return [];
        }

        // Clean BOM from first header
        $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
        $headers = array_map('trim', $headers);

        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) === count($headers)) {
                $rows[] = array_combine($headers, $data);
            }
        }

        fclose($handle);

        return $rows;
    }
}
