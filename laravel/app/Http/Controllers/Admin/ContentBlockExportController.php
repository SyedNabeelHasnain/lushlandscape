<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ContentBlockExportController extends Controller
{
    use HandlesAjaxRequests;

    private const EXPORT_SCHEMA_VERSION = 2;

    public function export(string $type, int $id)
    {
        $canonicalType = BlockBuilderService::canonicalPageType($type);

        if (! BlockBuilderService::supportsBlockExport($canonicalType)) {
            abort(404, 'Invalid page type.');
        }

        $pageId = $id === 0 ? null : $id;
        $blocks = BlockBuilderService::getUnifiedBlocks($canonicalType, $pageId);

        $payload = [
            'schema_version' => self::EXPORT_SCHEMA_VERSION,
            'page_type' => $canonicalType,
            'page_id' => $id,
            'exported_at' => Carbon::now()->toIso8601String(),
            'block_count' => $blocks->count(),
            'blocks' => $blocks->map(fn ($block) => $this->sanitizeExportedBlock($block))->values()->toArray(),
        ];

        $filename = "content-blocks-{$canonicalType}-{$id}-".Carbon::now()->format('Y-m-d-His').'.json';

        return Response::json($payload)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Type', 'application/json');
    }

    public function preview(Request $request, string $type, int $id)
    {
        $canonicalType = BlockBuilderService::canonicalPageType($type);

        if (! BlockBuilderService::supportsBlockExport($canonicalType)) {
            return $this->jsonError('Invalid page type.', [], 404);
        }

        $parsed = $this->parseImportedPayload($request, $canonicalType);
        if ($parsed instanceof \Illuminate\Http\JsonResponse) {
            return $parsed;
        }

        $existing = BlockBuilderService::getUnifiedBlocks($canonicalType, $id === 0 ? null : $id);
        $incoming = $parsed['blocks'];
        $data = $parsed['data'];

        return $this->jsonSuccess('Preview ready.', [
            'preview' => [
                'source_type' => $data['page_type'] ?? 'unknown',
                'source_id' => $data['page_id'] ?? 'unknown',
                'exported_at' => $data['exported_at'] ?? null,
                'current_count' => $existing->count(),
                'import_count' => $incoming->count(),
                'blocks' => $incoming->map(fn ($b) => [
                    'block_type' => $b['block_type'],
                    'is_layout_section' => (bool) ($b['is_layout_section'] ?? false),
                    'is_enabled' => $b['is_enabled'] ?? true,
                ])->toArray(),
            ],
        ]);
    }

    public function import(Request $request, string $type, int $id)
    {
        $canonicalType = BlockBuilderService::canonicalPageType($type);

        if (! BlockBuilderService::supportsBlockExport($canonicalType)) {
            return $this->jsonError('Invalid page type.', [], 404);
        }

        $parsed = $this->parseImportedPayload($request, $canonicalType);
        if ($parsed instanceof \Illuminate\Http\JsonResponse) {
            return $parsed;
        }

        $pageId = $id === 0 ? null : $id;
        $blocks = $parsed['blocks']->toArray();

        // Imports are intended to be portable across environments, so we fully replace
        // the target page surface rather than attempting DB-ID-based updates.
        BlockBuilderService::deleteAllBlocksForPage($canonicalType, $pageId);
        BlockBuilderService::saveUnifiedBlocks($canonicalType, $pageId, $blocks);

        return $this->jsonSuccess('Imported '.count($blocks).' page-builder items successfully.');
    }

    private function normalizeImportedBlock(array $block, int $index): array
    {
        return [
            'id' => null,
            'block_type' => $block['block_type'],
            'is_layout_section' => (bool) ($block['is_layout_section'] ?? false),
            'category' => $block['category'] ?? null,
            'is_enabled' => (bool) ($block['is_enabled'] ?? true),
            'show_on_desktop' => (bool) ($block['show_on_desktop'] ?? true),
            'show_on_tablet' => (bool) ($block['show_on_tablet'] ?? true),
            'show_on_mobile' => (bool) ($block['show_on_mobile'] ?? true),
            'content' => $this->stripInternalKeys(is_array($block['content'] ?? null) ? $block['content'] : []),
            'data_source' => is_array($block['data_source'] ?? null) ? $block['data_source'] : null,
            'styles' => is_array($block['styles'] ?? null) ? $block['styles'] : null,
            'custom_id' => $block['custom_id'] ?? null,
            'attributes' => is_array($block['attributes'] ?? null) ? $block['attributes'] : null,
            'animation' => $block['animation'] ?? null,
            'sort_order' => $block['sort_order'] ?? ($index + 1),
            'children' => collect($block['children'] ?? [])
                ->values()
                ->map(fn ($child, $childIndex) => $this->normalizeImportedBlock((array) $child, $childIndex))
                ->toArray(),
        ];
    }

    private function sanitizeExportedBlock(array $block): array
    {
        unset($block['id'], $block['parent_id'], $block['_uid'], $block['_open']);

        $block['content'] = $this->stripInternalKeys(is_array($block['content'] ?? null) ? $block['content'] : []);

        $block['children'] = collect($block['children'] ?? [])
            ->map(fn ($child) => $this->sanitizeExportedBlock((array) $child))
            ->values()
            ->toArray();

        return $block;
    }

    private function parseImportedPayload(Request $request, string $targetType)
    {
        $file = $request->file('file');
        if (! $file || $file->getClientOriginalExtension() !== 'json') {
            return $this->jsonError('Please upload a valid JSON file.');
        }

        $raw = file_get_contents($file->getRealPath());
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->jsonError('Invalid JSON: '.json_last_error_msg());
        }

        $validator = Validator::make($data, [
            'schema_version' => 'nullable|integer',
            'page_type' => 'nullable|string',
            'blocks' => 'required|array|min:1',
            'blocks.*.block_type' => 'required|string',
            'blocks.*.content' => 'nullable|array',
            'blocks.*.children' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return $this->jsonError('Invalid file structure.', $validator->errors()->toArray());
        }

        $sourceType = BlockBuilderService::canonicalPageType((string) ($data['page_type'] ?? $targetType));
        if ($sourceType !== $targetType) {
            return $this->jsonError("This builder JSON is for {$sourceType} pages and cannot be imported into {$targetType} pages.");
        }

        $incoming = collect($data['blocks'])
            ->values()
            ->map(fn ($block, $index) => $this->normalizeImportedBlock((array) $block, $index));

        $unsupportedBlockTypes = $this->unsupportedBlockTypes($incoming);
        if ($unsupportedBlockTypes !== []) {
            return $this->jsonError('This builder JSON contains unsupported block types.', [
                'block_types' => $unsupportedBlockTypes,
            ]);
        }

        return [
            'data' => $data,
            'blocks' => $incoming,
        ];
    }

    private function unsupportedBlockTypes(Collection $blocks): array
    {
        $supported = collect(BlockBuilderService::allTypes())
            ->pluck('key')
            ->all();

        $unsupported = [];
        $walk = function (Collection $items) use (&$walk, &$unsupported, $supported): void {
            $items->each(function (array $block) use (&$walk, &$unsupported, $supported): void {
                if (! in_array($block['block_type'], $supported, true)) {
                    $unsupported[] = $block['block_type'];
                }

                $children = collect($block['children'] ?? []);
                if ($children->isNotEmpty()) {
                    $walk($children);
                }
            });
        };

        $walk($blocks);

        return array_values(array_unique($unsupported));
    }

    private function stripInternalKeys(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        $cleaned = [];
        foreach ($value as $key => $item) {
            if (is_string($key) && str_starts_with($key, '_legacy_')) {
                continue;
            }

            $cleaned[$key] = $this->stripInternalKeys($item);
        }

        return $cleaned;
    }
}
