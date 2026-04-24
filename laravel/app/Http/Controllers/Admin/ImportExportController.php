<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Services\ImportService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ImportExportController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $tables = ExportService::getExportableTables();

        return View::make('admin.import-export.index', compact('tables'));
    }

    public function export(Request $request)
    {
        $request->validate(['table' => 'required|string']);

        return ExportService::export($request->input('table'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $tables = ExportService::getExportableTables();
        if (! array_key_exists($request->input('table'), $tables)) {
            return $this->jsonError('Invalid table selected.');
        }

        $result = ImportService::dryRun($request->input('table'), $request->file('file'));

        if (! $result['success']) {
            return $this->jsonError($result['error'] ?? 'Dry run failed.');
        }

        // Clean up old temporary imports (older than 1 hour) to prevent disk bloat
        $files = Storage::disk('local')->files('imports');
        $now = time();
        foreach ($files as $f) {
            if ($now - Storage::disk('local')->lastModified($f) > 3600) {
                Storage::disk('local')->delete($f);
            }
        }

        // Store the file temporarily for the confirm step
        $path = $request->file('file')->store('imports', 'local');

        return $this->jsonSuccess('Dry run complete.', [
            'preview' => $result,
            'temp_path' => $path,
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'temp_path' => 'required|string',
        ]);

        $tables = ExportService::getExportableTables();
        if (! array_key_exists($request->input('table'), $tables)) {
            return $this->jsonError('Invalid table selected.');
        }

        $tempPath = $request->input('temp_path');
        if (str_contains($tempPath, '..') || str_contains($tempPath, "\0")) {
            return $this->jsonError('Invalid file path.');
        }
        $fullPath = storage_path('app/private/'.$tempPath);
        $realDir = realpath(dirname($fullPath));
        if ($realDir === false || ! str_starts_with($realDir.'/', storage_path('app/private/'))) {
            return $this->jsonError('Invalid file path.');
        }
        if (! file_exists($fullPath)) {
            return $this->jsonError('Import file not found. Please upload again.');
        }

        $file = new UploadedFile($fullPath, basename($fullPath));
        $result = ImportService::import($request->input('table'), $file);

        // Remove temporary file immediately after import attempt
        Storage::disk('local')->delete($tempPath);

        if (! $result['success']) {
            return $this->jsonError($result['error'] ?? 'Import failed.', $result['errors'] ?? []);
        }

        $msg = "Import complete. Created: {$result['created']}, Updated: {$result['updated']}.";
        if (! empty($result['errors'])) {
            $msg .= ' Errors: '.count($result['errors']);
        }

        return $this->jsonSuccess($msg, ['details' => $result]);
    }
}
