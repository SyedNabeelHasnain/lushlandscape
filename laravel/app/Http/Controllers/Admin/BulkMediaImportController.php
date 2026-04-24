<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Services\MediaAcquisitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class BulkMediaImportController extends Controller
{
    use HandlesAjaxRequests;

    private const ALLOWED_MIMES = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'video/mp4', 'video/webm',
    ];

    private const MAX_FILE_SIZE = 20 * 1024 * 1024; // 20 MB

    public function index()
    {
        $recentImports = MediaAsset::query()
            ->whereNotNull('source_url')
            ->where('source_url', '!=', '')
            ->latest()
            ->take(20)
            ->get();

        return View::make('admin.bulk-import.index', compact('recentImports'));
    }

    /**
     * Process bulk import from JSON payload of media items.
     * Uses streamed response for real-time progress.
     */
    public function process(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1|max:100',
            'items.*.url' => 'required|url',
            'items.*.internal_title' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.default_alt_text' => 'nullable|string|max:500',
            'items.*.credit' => 'nullable|string|max:255',
            'items.*.image_purpose' => 'nullable|string|in:informative,decorative,functional,product,logo',
            'items.*.location_city' => 'nullable|string|max:100',
            'items.*.tags' => 'nullable|array',
        ]);

        $items = $request->input('items');
        $results = ['total' => count($items), 'success' => 0, 'skipped' => 0, 'failed' => 0, 'details' => []];

        return response()->stream(function () use ($items, &$results) {
            // Ensure output is not buffered
            if (ob_get_level()) {
                ob_end_clean();
            }

            $this->sendEvent('start', ['total' => count($items)]);

            foreach ($items as $index => $item) {
                $result = $this->importSingleItem($item, $index);
                $results['details'][] = $result;

                if ($result['status'] === 'success') {
                    $results['success']++;
                } elseif ($result['status'] === 'skipped') {
                    $results['skipped']++;
                } else {
                    $results['failed']++;
                }

                $this->sendEvent('progress', [
                    'index' => $index,
                    'total' => count($items),
                    'status' => $result['status'],
                    'title' => $item['internal_title'],
                    'message' => $result['message'],
                    'asset_id' => $result['asset_id'] ?? null,
                ]);
            }

            $this->sendEvent('complete', [
                'success' => $results['success'],
                'skipped' => $results['skipped'],
                'failed' => $results['failed'],
                'total' => $results['total'],
            ]);
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Upload a JSON dataset file for bulk import.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt|max:5120',
        ]);

        $contents = file_get_contents($request->file('file')->getRealPath());
        $data = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->jsonError('Invalid JSON file: '.json_last_error_msg());
        }

        if (! isset($data['items']) || ! is_array($data['items'])) {
            return $this->jsonError('JSON must contain an "items" array.');
        }

        $items = $data['items'];
        $valid = 0;
        $invalid = 0;
        $errors = [];

        foreach ($items as $i => $item) {
            if (empty($item['url']) || ! filter_var($item['url'], FILTER_VALIDATE_URL)) {
                $invalid++;
                $errors[] = "Item {$i}: Invalid or missing URL.";

                continue;
            }
            if (empty($item['internal_title'])) {
                $invalid++;
                $errors[] = "Item {$i}: Missing internal_title.";

                continue;
            }
            $valid++;
        }

        return $this->jsonSuccess("Dataset validated: {$valid} valid, {$invalid} invalid items.", [
            'valid' => $valid,
            'invalid' => $invalid,
            'errors' => array_slice($errors, 0, 20),
            'items' => $items,
        ]);
    }

    /**
     * Export all media assets as JSON.
     */
    public function exportLibrary()
    {
        $assets = MediaAsset::orderBy('id')->get();

        $exportData = [
            'exported_at' => now()->toIso8601String(),
            'total' => $assets->count(),
            'assets' => $assets->map(fn ($a) => [
                'id' => $a->id,
                'internal_title' => $a->internal_title,
                'canonical_filename' => $a->canonical_filename,
                'path' => $a->path,
                'media_type' => $a->media_type,
                'mime_type' => $a->mime_type,
                'extension' => $a->extension,
                'file_size' => $a->file_size,
                'width' => $a->width,
                'height' => $a->height,
                'aspect_ratio' => $a->aspect_ratio,
                'orientation' => $a->orientation,
                'description' => $a->description,
                'default_alt_text' => $a->default_alt_text,
                'default_caption' => $a->default_caption,
                'credit' => $a->credit,
                'image_purpose' => $a->image_purpose,
                'location_city' => $a->location_city,
                'tags' => $a->tags,
                'status' => $a->status,
                'social_preview_eligible' => $a->social_preview_eligible,
                'schema_eligible' => $a->schema_eligible,
            ])->toArray(),
        ];

        $filename = 'media-library-export-'.now()->format('Y-m-d-His').'.json';

        return response()->json($exportData)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Import media metadata from JSON (updates existing records by ID, creates new ones).
     */
    public function importLibrary(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt|max:10240',
        ]);

        $contents = file_get_contents($request->file('file')->getRealPath());
        $data = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->jsonError('Invalid JSON: '.json_last_error_msg());
        }

        if (! isset($data['assets']) || ! is_array($data['assets'])) {
            return $this->jsonError('JSON must contain an "assets" array.');
        }

        $updated = 0;
        $errors = [];

        foreach ($data['assets'] as $i => $assetData) {
            try {
                if (empty($assetData['id'])) {
                    $errors[] = "Item {$i}: Missing ID.";

                    continue;
                }

                $asset = MediaAsset::find($assetData['id']);
                if (! $asset) {
                    $errors[] = "Item {$i}: Asset ID {$assetData['id']} not found.";

                    continue;
                }

                $updateFields = array_intersect_key($assetData, array_flip([
                    'internal_title', 'description', 'default_alt_text', 'default_caption',
                    'credit', 'image_purpose', 'location_city', 'tags',
                    'social_preview_eligible', 'schema_eligible', 'status',
                ]));

                $asset->update($updateFields);
                $updated++;
            } catch (\Throwable $e) {
                $errors[] = "Item {$i}: ".$e->getMessage();
            }
        }

        $msg = "{$updated} assets updated.";
        if ($errors) {
            $msg .= ' '.count($errors).' errors.';
        }

        return $this->jsonSuccess($msg, [
            'updated' => $updated,
            'errors' => array_slice($errors, 0, 20),
        ]);
    }

    /**
     * Generate the complete media dataset for all site placements.
     */
    public function generateDataset()
    {
        $service = new MediaAcquisitionService;
        $dataset = $service->generateDataset();

        $filename = 'media-dataset-'.now()->format('Y-m-d-His').'.json';

        return response()->json($dataset)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    // ─── Private Helpers ─────────────────────────────────────────────────

    private function importSingleItem(array $item, int $index): array
    {
        $url = $item['url'];
        $title = $item['internal_title'];

        try {
            if (! $this->isUrlSafe($url)) {
                return ['status' => 'failed', 'message' => 'URL rejected: only public HTTP(S) URLs allowed.', 'index' => $index];
            }

            // Download with timeout and size limit
            $response = Http::withOptions([
                'timeout' => 30,
                'connect_timeout' => 10,
                'sink' => null,
            ])->withHeaders([
                'User-Agent' => 'LushLandscapeCMS/1.0 (Media Import)',
            ])->get($url);

            if (! $response->successful()) {
                return ['status' => 'failed', 'message' => "HTTP {$response->status()}", 'index' => $index];
            }

            $body = $response->body();
            $contentType = $response->header('Content-Type');
            $mimeType = explode(';', (string) $contentType)[0];
            $mimeType = trim(strtolower($mimeType));

            // Validate mime type
            if (! in_array($mimeType, self::ALLOWED_MIMES)) {
                return ['status' => 'failed', 'message' => "Invalid MIME: {$mimeType}", 'index' => $index];
            }

            // Validate file size
            if (strlen($body) > self::MAX_FILE_SIZE) {
                return ['status' => 'failed', 'message' => 'File exceeds 20MB limit', 'index' => $index];
            }

            if (strlen($body) < 1024) {
                return ['status' => 'failed', 'message' => 'File too small (likely error page)', 'index' => $index];
            }

            // Check duplicate by checksum
            $checksum = md5($body);
            $existing = MediaAsset::where('checksum', $checksum)->first();
            if ($existing) {
                return [
                    'status' => 'skipped',
                    'message' => "Duplicate of asset #{$existing->id}",
                    'asset_id' => $existing->id,
                    'index' => $index,
                ];
            }

            // Determine extension from mime
            $extMap = [
                'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif',
                'image/webp' => 'webp', 'video/mp4' => 'mp4', 'video/webm' => 'webm',
            ];
            $ext = $extMap[$mimeType];
            $mediaType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

            // Generate filename and store
            $filename = Str::slug($title).'-'.Str::random(6).'.'.$ext;
            Storage::disk('public')->put('media/'.$filename, $body);

            // Extract image dimensions
            $width = $height = $aspectRatio = null;
            $orientation = null;
            if ($mediaType === 'image') {
                $storedPath = Storage::disk('public')->path('media/'.$filename);
                $imgSize = @getimagesize($storedPath);
                if ($imgSize) {
                    $width = $imgSize[0];
                    $height = $imgSize[1];
                    $aspectRatio = $height > 0 ? round($width / $height, 2) : null;
                    $orientation = ($width >= $height) ? 'landscape' : 'portrait';
                }
            }

            // Create media asset record
            $asset = MediaAsset::create([
                'internal_title' => $title,
                'canonical_filename' => $filename,
                'disk' => 'public',
                'path' => 'media/'.$filename,
                'media_type' => $mediaType,
                'mime_type' => $mimeType,
                'extension' => $ext,
                'file_size' => strlen($body),
                'width' => $width,
                'height' => $height,
                'aspect_ratio' => $aspectRatio,
                'orientation' => $orientation,
                'description' => $item['description'] ?? $title,
                'default_alt_text' => $item['default_alt_text'] ?? $title,
                'default_caption' => $item['default_caption'] ?? null,
                'credit' => $item['credit'] ?? null,
                'image_purpose' => $item['image_purpose'] ?? 'informative',
                'location_city' => $item['location_city'] ?? null,
                'checksum' => $checksum,
                'tags' => $item['tags'] ?? [],
                'status' => 'active',
                'language' => 'en',
                'social_preview_eligible' => true,
                'schema_eligible' => true,
            ]);

            return [
                'status' => 'success',
                'message' => 'Imported successfully',
                'asset_id' => $asset->id,
                'index' => $index,
            ];
        } catch (\Throwable $e) {
            Log::warning("Bulk import failed for [{$title}]: ".$e->getMessage());

            return ['status' => 'failed', 'message' => Str::limit($e->getMessage(), 100), 'index' => $index];
        }
    }

    // Validates URL is safe for server-side requests (prevents SSRF)
    private function isUrlSafe(string $url): bool
    {
        $parsed = parse_url($url);
        if (! $parsed || ! isset($parsed['scheme'], $parsed['host'])) {
            return false;
        }

        if (! in_array(strtolower($parsed['scheme']), ['http', 'https'], true)) {
            return false;
        }

        $host = $parsed['host'];
        $ips = gethostbynamel($host);
        if ($ips === false) {
            return false;
        }

        foreach ($ips as $ip) {
            $long = ip2long($ip);
            if ($long === false) {
                return false;
            }

            if (
                ($long >> 24) === 127
                || ($long >> 24) === 10
                || ($long >> 20) === (172 << 4 | 1)
                || ($long >> 16) === (192 << 8 | 168)
                || ($long >> 16) === (169 << 8 | 254)
                || $long === 0
            ) {
                return false;
            }
        }

        return true;
    }

    private function sendEvent(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo 'data: '.json_encode($data, JSON_UNESCAPED_SLASHES)."\n\n";

        if (ob_get_level()) {
            ob_flush();
        }
        flush();
    }
}
