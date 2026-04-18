<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Services\MediaAcquisitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class MediaAssetController extends Controller
{
    use HandlesAjaxRequests;

    private const ALLOWED_MIMES = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp',
        'video/mp4', 'video/webm',
    ];

    public function index(Request $request)
    {
        $query = MediaAsset::orderByDesc('created_at');

        if ($request->filled('search')) {
            $query->where('internal_title', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $request->search).'%');
        }
        if ($request->filled('type')) {
            $query->where('media_type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $media = $query->paginate(24);

        // Stats for the overview panel
        $stats = [
            'total' => MediaAsset::count(),
            'with_files' => MediaAsset::where('file_size', '>', 0)->whereNotNull('checksum')->count(),
            'with_urls' => MediaAsset::whereNotNull('source_url')->where('source_url', '!=', '')->count(),
            'pending' => MediaAsset::where(function ($q) {
                $q->where('file_size', 0)->orWhereNull('checksum');
            })->whereNotNull('source_url')->where('source_url', '!=', '')->count(),
            'no_url' => MediaAsset::where(function ($q) {
                $q->where('file_size', 0)->orWhereNull('checksum');
            })->where(function ($q) {
                $q->whereNull('source_url')->orWhere('source_url', '');
            })->count(),
        ];

        return View::make('admin.media.index', compact('media', 'stats'));
    }

    public function create()
    {
        return View::make('admin.media.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm|max:20480',
            'internal_title' => 'required|string|max:255',
            'description' => 'required|string',
            'default_alt_text' => 'nullable|string|max:500',
            'default_caption' => 'nullable|string',
            'credit' => 'nullable|string|max:255',
            'image_purpose' => 'nullable|string|max:50',
            'location_city' => 'nullable|string|max:255',
            'focal_x' => 'nullable|numeric|min:0|max:1',
            'focal_y' => 'nullable|numeric|min:0|max:1',
            'social_preview_eligible' => 'boolean',
            'schema_eligible' => 'boolean',
        ]);

        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();
        $filename = Str::slug($request->internal_title).'-'.Str::random(6).'.'.$ext;
        $path = $file->storeAs('media', $filename, 'public');

        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
        $isVideo = in_array($ext, ['mp4', 'webm']);

        $assetData = [
            'internal_title' => $request->internal_title,
            'canonical_filename' => $filename,
            'disk' => 'public',
            'path' => $path,
            'media_type' => $isImage ? 'image' : ($isVideo ? 'video' : 'document'),
            'mime_type' => $file->getMimeType(),
            'extension' => $ext,
            'file_size' => $file->getSize(),
            'description' => $request->description,
            'default_alt_text' => $request->default_alt_text,
            'default_caption' => $request->default_caption,
            'credit' => $request->credit ?? 'Super WMS',
            'image_purpose' => $request->image_purpose ?? 'informative',
            'location_city' => $request->location_city,
            'social_preview_eligible' => $request->boolean('social_preview_eligible'),
            'schema_eligible' => $request->boolean('schema_eligible'),
            'checksum' => md5_file($file->getRealPath()),
            'language' => 'en',
        ];

        if ($request->filled('focal_x') && $request->filled('focal_y')) {
            $assetData['focal_point'] = [
                'x' => (float) $request->input('focal_x'),
                'y' => (float) $request->input('focal_y'),
            ];
        }

        if ($isImage && $ext !== 'svg') {
            $imgSize = getimagesize($file->getRealPath());
            if ($imgSize) {
                $assetData['width'] = $imgSize[0];
                $assetData['height'] = $imgSize[1];
                $assetData['aspect_ratio'] = round($imgSize[0] / max($imgSize[1], 1), 2);
                $assetData['orientation'] = $imgSize[0] >= $imgSize[1] ? 'landscape' : 'portrait';
            }
        }

        $existing = MediaAsset::where('checksum', $assetData['checksum'])->first();
        if ($existing) {
            Storage::disk('public')->delete($path);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Duplicate file: '.$existing->internal_title, 'message' => 'Duplicate file: '.$existing->internal_title, 'asset' => $existing], 422);
            }

            return Redirect::back()->with('error', 'This file already exists in the media library: '.$existing->internal_title);
        }

        $asset = MediaAsset::create($assetData);

        if ($request->expectsJson()) {
            return response()->json([
                'asset' => $asset,
                'message' => 'Media uploaded successfully.',
                'redirect' => route('admin.media.edit', $asset),
            ]);
        }

        return Redirect::route('admin.media.index')
            ->with('success', 'Media uploaded successfully.');
    }

    /** Paginated JSON list for the media-picker modal. */
    public function json(Request $request)
    {
        if ($request->filled('ids')) {
            $ids = collect(explode(',', (string) $request->input('ids')))
                ->map(fn ($id) => (int) trim($id))
                ->filter(fn ($id) => $id > 0)
                ->values();

            if ($ids->isEmpty()) {
                return response()->json([]);
            }

            $assets = MediaAsset::whereIn('id', $ids)
                ->get()
                ->sortBy(fn (MediaAsset $asset) => $ids->search($asset->id))
                ->values();

            return response()->json($assets);
        }

        if ($request->filled('id')) {
            $asset = MediaAsset::find($request->integer('id'));

            if (! $asset) {
                return response()->json(['message' => 'Media asset not found.'], 404);
            }

            return response()->json($asset);
        }

        $query = MediaAsset::orderByDesc('created_at');

        if ($request->filled('search')) {
            $query->where('internal_title', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $request->search).'%');
        }
        if ($request->filled('type')) {
            $query->where('media_type', $request->type);
        }

        $media = $query->paginate(24);

        return response()->json($media);
    }

    public function edit(MediaAsset $medium)
    {
        return View::make('admin.media.form', ['asset' => $medium]);
    }

    public function update(Request $request, MediaAsset $medium)
    {
        $validated = $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm|max:20480',
            'internal_title' => 'required|string|max:255',
            'description' => 'required|string',
            'default_alt_text' => 'nullable|string|max:500',
            'default_caption' => 'nullable|string',
            'credit' => 'nullable|string|max:255',
            'image_purpose' => 'nullable|string|max:50',
            'location_city' => 'nullable|string|max:255',
            'source_url' => 'nullable|url|max:2000',
            'focal_x' => 'nullable|numeric|min:0|max:1',
            'focal_y' => 'nullable|numeric|min:0|max:1',
            'social_preview_eligible' => 'boolean',
            'schema_eligible' => 'boolean',
        ]);

        $validated['social_preview_eligible'] = $request->boolean('social_preview_eligible');
        $validated['schema_eligible'] = $request->boolean('schema_eligible');
        unset($validated['file']);

        if ($request->filled('focal_x') && $request->filled('focal_y')) {
            $validated['focal_point'] = [
                'x' => (float) $request->input('focal_x'),
                'y' => (float) $request->input('focal_y'),
            ];
        }

        unset($validated['focal_x'], $validated['focal_y']);

        // Handle file replacement
        if ($request->hasFile('file')) {
            $this->replaceFile($medium, $request->file('file'), $validated);
        }

        $medium->update($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Media updated successfully.');
        }

        return Redirect::route('admin.media.index')
            ->with('success', 'Media updated successfully.');
    }

    public function destroy(Request $request, MediaAsset $medium)
    {
        if ($medium->placements()->count() > 0) {
            if ($this->isAjax($request)) {
                return $this->jsonError('Cannot delete media that is assigned to pages. Remove placements first.');
            }

            return Redirect::back()->with('error', 'Cannot delete media that is assigned to pages. Remove placements first.');
        }

        Storage::disk('public')->delete($medium->path);
        $medium->variants()->delete();
        $medium->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Media deleted.');
        }

        return Redirect::route('admin.media.index')
            ->with('success', 'Media deleted.');
    }

    /**
     * Download image from source_url for a single media asset.
     * Accepts optional URL in request body to save and use.
     */
    public function downloadSingle(Request $request, MediaAsset $medium)
    {
        // Accept URL from request body (from the form input)
        $url = $request->input('url');
        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            $medium->update(['source_url' => $url]);
        }

        if (empty($medium->source_url)) {
            return $this->jsonError('No source URL set for this asset. Enter a URL and try again.');
        }

        if (! $this->isUrlSafe($medium->source_url)) {
            return $this->jsonError('URL rejected: only public HTTP(S) URLs are allowed.');
        }

        $result = $this->downloadFromUrl($medium, $medium->source_url);

        if ($result['success']) {
            return $this->jsonSuccess($result['message']);
        }

        return $this->jsonError($result['message']);
    }

    /**
     * Bulk download all assets that have source_url but no file.
     * Uses SSE for real-time progress.
     */
    public function downloadAll()
    {
        $assets = MediaAsset::whereNotNull('source_url')
            ->where('source_url', '!=', '')
            ->where(function ($q) {
                $q->where('file_size', 0)->orWhereNull('checksum');
            })
            ->orderBy('id')
            ->get();

        if ($assets->isEmpty()) {
            return response()->json(['message' => 'No assets pending download.']);
        }

        return response()->stream(function () use ($assets) {
            if (ob_get_level()) {
                ob_end_clean();
            }

            $this->sendSSE('start', ['total' => $assets->count()]);

            $success = 0;
            $failed = 0;
            $skipped = 0;

            /** @var MediaAsset $asset */
            foreach ($assets as $i => $asset) {
                $result = $this->downloadFromUrl($asset, $asset->source_url);

                $status = $result['success'] ? 'success' : ($result['skipped'] ?? false ? 'skipped' : 'failed');
                if ($result['success']) {
                    $success++;
                } elseif ($result['skipped'] ?? false) {
                    $skipped++;
                } else {
                    $failed++;
                }

                $this->sendSSE('progress', [
                    'index' => $i,
                    'total' => $assets->count(),
                    'status' => $status,
                    'title' => Str::limit($asset->internal_title, 50),
                    'message' => $result['message'],
                ]);
            }

            $this->sendSSE('complete', [
                'success' => $success,
                'failed' => $failed,
                'skipped' => $skipped,
                'total' => $assets->count(),
            ]);
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Use the curated official-media catalog to find and populate source_url for assets without one.
     * Uses SSE for real-time progress.
     */
    public function populateUrls()
    {
        $assets = MediaAsset::where(function ($q) {
            $q->whereNull('source_url')->orWhere('source_url', '');
        })
            ->where(function ($q) {
                $q->where('file_size', 0)->orWhereNull('checksum');
            })
            ->orderBy('id')
            ->get();

        if ($assets->isEmpty()) {
            return response()->json(['message' => 'All assets already have source URLs.']);
        }

        $service = new MediaAcquisitionService;

        return response()->stream(function () use ($assets, $service) {
            if (ob_get_level()) {
                ob_end_clean();
            }

            $this->sendSSE('start', ['total' => $assets->count()]);

            $found = 0;
            $notFound = 0;

            /** @var MediaAsset $asset */
            foreach ($assets as $i => $asset) {
                // Build search query from asset metadata
                $query = $this->buildSearchQuery($asset);

                $result = $service->fetchImage($query, 'landscape', 1200);

                if ($result) {
                    $asset->update([
                        'source_url' => $result['url'],
                        'credit' => $result['credit'],
                    ]);
                    $found++;
                    $status = 'success';
                    $message = 'URL found';
                } else {
                    $notFound++;
                    $status = 'failed';
                    $message = 'No match found';
                }

                $this->sendSSE('progress', [
                    'index' => $i,
                    'total' => $assets->count(),
                    'status' => $status,
                    'title' => Str::limit($asset->internal_title, 50),
                    'message' => $message,
                    'query' => Str::limit($query, 60),
                ]);

                usleep(100000);
            }

            $this->sendSSE('complete', [
                'found' => $found,
                'notFound' => $notFound,
                'total' => $assets->count(),
            ]);
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    // ─── Private Helpers ─────────────────────────────────────────────────

    private function replaceFile(MediaAsset $medium, $file, array &$validated): void
    {
        $ext = $file->getClientOriginalExtension();
        $filename = Str::slug($validated['internal_title']).'-'.Str::random(6).'.'.$ext;
        $path = $file->storeAs('media', $filename, 'public');

        // Delete old file
        if ($medium->path) {
            Storage::disk('public')->delete($medium->path);
        }

        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $isVideo = in_array($ext, ['mp4', 'webm']);

        $validated['canonical_filename'] = $filename;
        $validated['path'] = $path;
        $validated['media_type'] = $isImage ? 'image' : ($isVideo ? 'video' : 'document');
        $validated['mime_type'] = $file->getMimeType();
        $validated['extension'] = $ext;
        $validated['file_size'] = $file->getSize();
        $validated['checksum'] = md5_file($file->getRealPath());

        if ($isImage) {
            $imgSize = getimagesize($file->getRealPath());
            if ($imgSize) {
                $validated['width'] = $imgSize[0];
                $validated['height'] = $imgSize[1];
                $validated['aspect_ratio'] = round($imgSize[0] / max($imgSize[1], 1), 2);
                $validated['orientation'] = $imgSize[0] >= $imgSize[1] ? 'landscape' : 'portrait';
            }
        }
    }

    /**
     * Download a file from URL and save it as the asset's file.
     */
    private function downloadFromUrl(MediaAsset $asset, string $url): array
    {
        if (! $this->isUrlSafe($url)) {
            return ['success' => false, 'message' => 'URL rejected: only public HTTP(S) URLs are allowed.'];
        }

        try {
            $response = Http::withOptions([
                'timeout' => 30,
                'connect_timeout' => 10,
            ])->withHeaders([
                'User-Agent' => 'LushLandscapeCMS/1.0',
            ])->get($url);

            if (! $response->successful()) {
                return ['success' => false, 'message' => "HTTP {$response->status()}"];
            }

            $body = $response->body();
            $contentType = $response->header('Content-Type');
            $mimeType = trim(strtolower(explode(';', (string) $contentType)[0]));

            if (! in_array($mimeType, self::ALLOWED_MIMES)) {
                return ['success' => false, 'message' => "Invalid MIME: {$mimeType}"];
            }

            if (strlen($body) < 1024) {
                return ['success' => false, 'message' => 'File too small (likely error page)'];
            }

            if (strlen($body) > 20 * 1024 * 1024) {
                return ['success' => false, 'message' => 'File exceeds 20MB'];
            }

            // Check for duplicate by checksum
            $checksum = md5($body);
            $duplicate = MediaAsset::where('checksum', $checksum)->where('id', '!=', $asset->id)->first();
            if ($duplicate) {
                return ['success' => false, 'skipped' => true, 'message' => "Duplicate of #{$duplicate->id}"];
            }

            $extMap = [
                'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif',
                'image/webp' => 'webp', 'video/mp4' => 'mp4', 'video/webm' => 'webm',
            ];
            $ext = $extMap[$mimeType];

            // Delete old file if it exists
            if ($asset->path && Storage::disk('public')->exists($asset->path)) {
                Storage::disk('public')->delete($asset->path);
            }

            // Store to the path defined in the seeder (or generate new one)
            $filename = Str::slug($asset->internal_title).'-'.Str::random(6).'.'.$ext;
            $path = 'media/'.$filename;
            Storage::disk('public')->put($path, $body);

            // Extract dimensions
            $width = $height = $aspectRatio = null;
            $orientation = null;
            $mediaType = str_starts_with($mimeType, 'video/') ? 'video' : 'image';

            if ($mediaType === 'image') {
                $storedPath = Storage::disk('public')->path($path);
                $imgSize = @getimagesize($storedPath);
                if ($imgSize) {
                    $width = $imgSize[0];
                    $height = $imgSize[1];
                    $aspectRatio = $height > 0 ? round($width / $height, 2) : null;
                    $orientation = ($width >= $height) ? 'landscape' : 'portrait';
                }
            }

            $asset->update([
                'canonical_filename' => $filename,
                'path' => $path,
                'media_type' => $mediaType,
                'mime_type' => $mimeType,
                'extension' => $ext,
                'file_size' => strlen($body),
                'width' => $width,
                'height' => $height,
                'aspect_ratio' => $aspectRatio,
                'orientation' => $orientation,
                'checksum' => $checksum,
                'status' => 'active',
            ]);

            return ['success' => true, 'message' => "{$width}x{$height} {$ext}"];
        } catch (\Throwable $e) {
            Log::warning("Media download failed [{$asset->id}]: ".$e->getMessage());

            return ['success' => false, 'message' => Str::limit($e->getMessage(), 80)];
        }
    }

    /**
     * Build an intelligent search query from a media asset's metadata.
     */
    private function buildSearchQuery(MediaAsset $asset): string
    {
        $parts = [];

        // Use editorial_class to determine what kind of image
        $editorialMap = [
            'hero' => 'professional',
            'gallery' => 'detail view',
            'informative' => 'process',
        ];
        if ($asset->editorial_class && isset($editorialMap[$asset->editorial_class])) {
            $parts[] = $editorialMap[$asset->editorial_class];
        }

        // Extract service type from title or tags
        $title = strtolower($asset->internal_title ?? '');
        $serviceKeywords = [
            'interlocking driveways' => 'interlocking paver driveway',
            'interlocking patios' => 'paver patio outdoor living',
            'walkways' => 'stone walkway residential',
            'natural stone' => 'flagstone patio natural stone',
            'flagstone' => 'flagstone patio natural stone',
            'porcelain' => 'porcelain paver modern patio',
            'concrete driveways' => 'concrete driveway residential',
            'concrete patios' => 'stamped concrete patio',
            'restoration' => 'paver restoration cleaning',
            'sealing' => 'paver sealing finish',
            'repair' => 'paver repair maintenance',
            'lift' => 'paver lift relay repair',
            'retaining wall' => 'retaining wall professional',
            'sod' => 'sod installation lawn',
            'grading' => 'lawn grading topsoil',
            'artificial turf' => 'artificial turf synthetic grass',
            'garden design' => 'garden design planting landscape',
            'planting' => 'garden planting shrubs perennial',
            'landscape lighting' => 'landscape lighting outdoor night',
            'lighting' => 'landscape lighting garden path',
        ];

        $matched = false;
        foreach ($serviceKeywords as $keyword => $query) {
            if (str_contains($title, $keyword)) {
                $parts[] = $query;
                $matched = true;
                break;
            }
        }

        if (! $matched && $asset->description) {
            // Use first few meaningful words from description
            $desc = preg_replace('/\b(image|photo|for|the|in|of|and|a|an)\b/i', '', $asset->description);
            $words = array_filter(array_slice(explode(' ', trim($desc)), 0, 5));
            if ($words) {
                $parts[] = implode(' ', $words);
            }
        }

        // Add location context
        if ($asset->location_city) {
            $parts[] = $asset->location_city.' Our Region';
        } else {
            $parts[] = 'residential professional';
        }

        // Use tags for additional context
        if (is_array($asset->tags) && ! empty($asset->tags)) {
            $tagStr = implode(' ', array_slice($asset->tags, 0, 2));
            if ($tagStr && ! str_contains(implode(' ', $parts), $tagStr)) {
                $parts[] = $tagStr;
            }
        }

        return implode(' ', $parts);
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

            // Reject private/reserved IP ranges
            if (
                ($long >> 24) === 127                             // 127.0.0.0/8
                || ($long >> 24) === 10                           // 10.0.0.0/8
                || ($long >> 20) === (172 << 4 | 1)               // 172.16.0.0/12
                || ($long >> 16) === (192 << 8 | 168)             // 192.168.0.0/16
                || ($long >> 16) === (169 << 8 | 254)             // 169.254.0.0/16
                || $long === 0                                    // 0.0.0.0
            ) {
                return false;
            }
        }

        return true;
    }

    private function sendSSE(string $event, array $data): void
    {
        echo "event: {$event}\n";
        echo 'data: '.json_encode($data, JSON_UNESCAPED_SLASHES)."\n\n";
        if (ob_get_level()) {
            ob_flush();
        }
        flush();
    }
}
