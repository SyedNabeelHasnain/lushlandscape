<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlockCapabilityAuditService
{
    private const SUPPORTED_CONTENT_FIELD_TYPES = [
        'text',
        'number',
        'select_model',
        'media_multi',
        'textarea',
        'code',
        'richtext',
        'select',
        'toggle',
        'media',
        'repeater',
    ];

    private const SUPPORTED_STYLE_FIELD_TYPES = [
        'select',
        'toggle',
        'text',
        'number',
        'range',
        'media',
    ];

    private const SHARED_RENDERER_STYLE_KEYS = [
        'bg_color',
        'bg_image_id',
        'bg_overlay',
        'bg_overlay_opacity',
        'surface_style',
        'glass_effect',
        'text_color',
        'text_align',
        'font_size',
        'font_weight',
        'spacing_preset',
        'padding_top',
        'padding_bottom',
        'padding_left',
        'padding_right',
        'margin_top',
        'margin_bottom',
        'section_shell',
        'divider_style',
        'rounded',
        'border',
        'shadow',
        'overflow',
        'z_index',
        'max_width',
        'custom_class',
    ];

    public function audit(): array
    {
        $blockMatrix = collect(config('blocks.types', []))
            ->map(fn (array $config, string $blockType) => $this->blockAuditRow($blockType, $config))
            ->sortBy('key')
            ->values()
            ->all();

        $styleFieldMatrix = $this->styleFieldMatrix();
        $unsupportedContentFields = collect($blockMatrix)
            ->flatMap(function (array $block) {
                return collect($block['unsupported_content_fields'])
                    ->map(fn (array $field) => [
                        'block_type' => $block['key'],
                        'field_key' => $field['key'],
                        'field_type' => $field['type'],
                    ])
                    ->all();
            })
            ->values()
            ->all();

        $blocksMissingRenderSurface = collect($blockMatrix)
            ->filter(fn (array $block) => ! $block['has_render_surface'])
            ->pluck('key')
            ->values()
            ->all();


        return [
            'summary' => [
                'registered_block_types' => count($blockMatrix),
                'blocks_missing_render_surface' => count($blocksMissingRenderSurface),
                'unsupported_content_fields' => count($unsupportedContentFields),
                'declared_style_fields' => count($styleFieldMatrix),
                'style_fields_not_rendered' => count(array_filter($styleFieldMatrix, fn (array $field) => ! $field['frontend_supported'])),
            ],
            'supported_content_field_types' => self::SUPPORTED_CONTENT_FIELD_TYPES,
            'supported_style_field_types' => self::SUPPORTED_STYLE_FIELD_TYPES,
            'shared_renderer_style_keys' => self::SHARED_RENDERER_STYLE_KEYS,
            'style_field_matrix' => $styleFieldMatrix,
            'style_keys_not_rendered' => collect($styleFieldMatrix)
                ->filter(fn (array $field) => ! $field['frontend_supported'])
                ->pluck('key')
                ->values()
                ->all(),
            'blocks' => $blockMatrix,
            'blocks_missing_render_surface' => $blocksMissingRenderSurface,
            'unsupported_content_fields' => $unsupportedContentFields,
        ];
    }

    public function writeMarkdownReport(string $targetPath, ?array $audit = null): string
    {
        $audit ??= $this->audit();

        $absolutePath = $this->resolvePath($targetPath);
        File::ensureDirectoryExists(dirname($absolutePath));
        File::put($absolutePath, $this->markdownReport($audit));

        return $absolutePath;
    }

    public function markdownReport(array $audit): string
    {
        $lines = [
            '# Block Capability Matrix',
            '',
            '## Summary',
            '',
            '- Registered block types: '.$audit['summary']['registered_block_types'],
            '- Blocks missing render surfaces: '.$audit['summary']['blocks_missing_render_surface'],
            '- Unsupported content fields: '.$audit['summary']['unsupported_content_fields'],
            '- Declared style fields: '.$audit['summary']['declared_style_fields'],
            '- Style fields not rendered by shared renderer: '.$audit['summary']['style_fields_not_rendered'],
            '',
            '## Style Parity',
            '',
            '| Key | Type | Editor | Frontend |',
            '| --- | --- | --- | --- |',
        ];

        foreach ($audit['style_field_matrix'] as $field) {
            $lines[] = sprintf(
                '| `%s` | `%s` | %s | %s |',
                $field['key'],
                $field['type'],
                $field['editor_supported'] ? 'Yes' : 'No',
                $field['frontend_supported'] ? 'Yes' : 'No'
            );
        }

        $lines[] = '';
        $lines[] = '## Missing Render Surfaces';
        $lines[] = '';

        if ($audit['blocks_missing_render_surface'] === []) {
            $lines[] = '- None';
        } else {
            foreach ($audit['blocks_missing_render_surface'] as $blockType) {
                $lines[] = '- `'.$blockType.'`';
            }
        }

        $lines[] = '';
        $lines[] = '## Legacy Renderer Views';
        $lines[] = '';

            $lines[] = '- None';
        } else {
                $lines[] = '- `'.$path.'`';
            }
        }

        $lines[] = '';
        $lines[] = '## Block Matrix';
        $lines[] = '';
        $lines[] = '| Block | Category | Render Surface | Unsupported Content Fields |';
        $lines[] = '| --- | --- | --- | --- |';

        foreach ($audit['blocks'] as $block) {
            $renderSurface = $block['render_surface']
                ? sprintf('`%s`', $block['render_surface']['kind'])
                : 'Missing';
            $unsupported = collect($block['unsupported_content_fields'])
                ->map(fn (array $field) => '`'.$field['key'].'` (`'.$field['type'].'`)')
                ->implode(', ');

            $lines[] = sprintf(
                '| `%s` | `%s` | %s | %s |',
                $block['key'],
                $block['category'],
                $renderSurface,
                $unsupported !== '' ? $unsupported : 'None'
            );
        }

        return implode(PHP_EOL, $lines).PHP_EOL;
    }

    private function blockAuditRow(string $blockType, array $config): array
    {
        $contentFields = collect($config['content_fields'] ?? [])
            ->map(fn (array $field) => [
                'key' => $field['key'],
                'type' => $field['type'],
                'editor_supported' => in_array($field['type'], self::SUPPORTED_CONTENT_FIELD_TYPES, true),
            ])
            ->values()
            ->all();

        $renderSurface = $this->renderSurface($blockType);

        return [
            'key' => $blockType,
            'label' => $config['label'] ?? $blockType,
            'category' => $config['category'] ?? 'content',
            'content_fields' => $contentFields,
            'content_field_types' => collect($contentFields)->pluck('type')->unique()->values()->all(),
            'unsupported_content_fields' => array_values(array_filter($contentFields, fn (array $field) => ! $field['editor_supported'])),
            'render_surface' => $renderSurface,
            'has_render_surface' => $renderSurface !== null && ($renderSurface['exists'] ?? false),
        ];
    }

    private function styleFieldMatrix(): array
    {
        return collect(BlockBuilderService::styleFields())
            ->map(fn (array $field) => [
                'key' => $field['key'],
                'label' => $field['label'],
                'type' => $field['type'],
                'tab' => $field['tab'] ?? null,
                'editor_supported' => in_array($field['type'], self::SUPPORTED_STYLE_FIELD_TYPES, true),
                'frontend_supported' => in_array($field['key'], self::SHARED_RENDERER_STYLE_KEYS, true),
            ])
            ->values()
            ->all();
    }

    private function renderSurface(string $blockType): ?array
    {
        $layoutSurface = BlockBuilderService::layoutSectionViewMap()[$blockType] ?? null;
        if ($layoutSurface !== null) {
            $path = $layoutSurface['type'] === 'component'
                ? $this->componentViewPath($layoutSurface['name'])
                : $this->viewPath($layoutSurface['name']);

            return [
                'kind' => $layoutSurface['type'] === 'component' ? 'layout_component' : 'layout_view',
                'target' => $layoutSurface['name'],
                'path' => $this->relativePath($path),
                'exists' => File::exists($path),
            ];
        }

        $viewKey = Str::replace('_', '-', $blockType);
        $blockView = 'frontend.blocks.'.$viewKey;
        $blockViewPath = $this->viewPath($blockView);
        if (File::exists($blockViewPath)) {
            return [
                'kind' => 'block_view',
                'target' => $blockView,
                'path' => $this->relativePath($blockViewPath),
                'exists' => true,
            ];
        }

        $partialView = 'frontend.blocks.partials.'.$viewKey;
        $partialViewPath = $this->viewPath($partialView);
        if (File::exists($partialViewPath)) {
            return [
                'kind' => 'partial_view',
                'target' => $partialView,
                'path' => $this->relativePath($partialViewPath),
                'exists' => true,
            ];
        }

        return null;
    }

    private function bladeViewsContaining(string $needle): array
    {
        return collect(File::allFiles(resource_path('views')))
            ->filter(fn (\SplFileInfo $file) => Str::endsWith($file->getFilename(), '.blade.php'))
            ->map(fn (\SplFileInfo $file) => $file->getRealPath())
            ->filter(fn (string $path) => str_contains(File::get($path), $needle))
            ->map(fn (string $path) => $this->relativePath($path))
            ->sort()
            ->values()
            ->all();
    }

    private function viewPath(string $viewName): string
    {
        return resource_path('views/'.str_replace('.', '/', $viewName).'.blade.php');
    }

    private function componentViewPath(string $componentName): string
    {
        return resource_path('views/components/'.str_replace('.', '/', $componentName).'.blade.php');
    }

    private function relativePath(string $absolutePath): string
    {
        return Str::after($absolutePath, base_path().DIRECTORY_SEPARATOR);
    }

    private function resolvePath(string $targetPath): string
    {
        if (Str::startsWith($targetPath, ['/'])) {
            return $targetPath;
        }

        return base_path($targetPath);
    }
}
