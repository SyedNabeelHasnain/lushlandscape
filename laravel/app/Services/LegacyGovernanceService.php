<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LegacyGovernanceService
{
    public static function strictEnabled(): bool
    {
        $value = config('app.legacy_strict', false);

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function denyLegacyWrite(string $action, array $context = []): void
    {
        $payload = array_merge([
            'action' => $action,
        ], $context);

        Log::error('Legacy write denied.', $payload);

        throw new \RuntimeException('Legacy authoring is disabled. Use unified blocks only.');
    }

    public static function legacyRead(string $source, string $pageType, mixed $pageId = null, array $context = []): void
    {
        $payload = array_merge([
            'source' => $source,
            'page_type' => $pageType,
            'page_id' => $pageId,
        ], $context);

        Log::warning('Legacy presentation read detected.', $payload);

        if (! self::strictEnabled()) {
            return;
        }

        if (app()->runningInConsole()) {
            return;
        }

        $strictPageTypes = config('blocks.strict_unified_page_types', []);
        if (in_array($pageType, $strictPageTypes, true)) {
            throw new \RuntimeException("Legacy presentation read is not allowed in strict mode for '{$pageType}'.");
        }
    }

    public static function hasLegacyMarkers(array $blocks): bool
    {
        $scan = function (array $node) use (&$scan): bool {
            $content = is_array($node['content'] ?? null) ? $node['content'] : [];
            foreach (array_keys($content) as $key) {
                if (str_starts_with($key, '_legacy_')) {
                    return true;
                }
            }

            $children = is_array($node['children'] ?? null) ? $node['children'] : [];
            foreach ($children as $child) {
                if (is_array($child) && $scan($child)) {
                    return true;
                }
            }

            return false;
        };

        foreach ($blocks as $block) {
            if (is_array($block) && $scan($block)) {
                return true;
            }
        }

        return false;
    }
}
