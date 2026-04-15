<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;

class BlockGovernanceService
{
    public static function validateBlocksForPage(string $pageType, array $blocks): void
    {
        foreach ($blocks as $block) {
            self::validateBlockTree($pageType, $block);
        }
    }

    private static function validateBlockTree(string $pageType, array $block, ?string $parentBlockType = null): void
    {
        $blockType = (string) ($block['block_type'] ?? '');
        if ($blockType === '') {
            throw new \RuntimeException('Block type is required.');
        }

        $typeConfig = Config::get('blocks.types.'.$blockType, []);
        if ($typeConfig === []) {
            throw new \RuntimeException("Unknown block type '{$blockType}'.");
        }

        $category = (string) ($typeConfig['category'] ?? 'content');
        if ($category === 'theme' && $pageType !== 'theme_layout') {
            throw new \RuntimeException("Theme blocks are only allowed on theme_layout pages (found '{$blockType}' on '{$pageType}').");
        }

        $governance = is_array($typeConfig['governance'] ?? null) ? $typeConfig['governance'] : [];
        $allowedPageTypes = $governance['allowed_page_types'] ?? null;
        if (is_array($allowedPageTypes) && $allowedPageTypes !== [] && ! in_array($pageType, $allowedPageTypes, true)) {
            throw new \RuntimeException("Block '{$blockType}' is not allowed on '{$pageType}'.");
        }

        $isEnabled = (bool) ($block['is_enabled'] ?? true);
        if ($isEnabled) {
            $content = is_array($block['content'] ?? null) ? $block['content'] : [];
            $variant = is_string($content['variant'] ?? null) ? $content['variant'] : null;
            $contentFields = is_array($typeConfig['content_fields'] ?? null) ? $typeConfig['content_fields'] : [];
            $contentFieldKeys = [];
            foreach ($contentFields as $field) {
                if (is_array($field) && is_string($field['key'] ?? null)) {
                    $contentFieldKeys[] = $field['key'];
                }
            }
            $supportsButtonUrl = in_array('button_url', $contentFieldKeys, true);

            $variants = $governance['variants'] ?? null;
            if ($variant !== null && is_array($variants) && $variants !== []) {
                $allowedVariants = array_values(array_filter($variants, fn ($v) => is_string($v)));
                if ($allowedVariants === []) {
                    $allowedVariants = array_values(array_filter(array_keys($variants), fn ($v) => is_string($v)));
                }

                if ($allowedVariants !== [] && ! in_array($variant, $allowedVariants, true)) {
                    throw new \RuntimeException("Block '{$blockType}' has invalid variant '{$variant}'.");
                }
            }

            $required = $governance['required_fields'] ?? [];
            if (is_array($required) && $required !== []) {
                $requiredFields = array_values(array_filter($required, fn ($v) => is_string($v)));
                if ($variant !== null && is_array($required[$variant] ?? null)) {
                    $requiredFields = array_values(array_filter($required[$variant], fn ($v) => is_string($v)));
                }

                foreach ($requiredFields as $field) {
                    $value = $content[$field] ?? null;
                    if (is_string($value)) {
                        if (trim($value) !== '') {
                            continue;
                        }
                    } elseif (is_numeric($value)) {
                        continue;
                    } elseif (is_array($value)) {
                        if ($value !== []) {
                            continue;
                        }
                    } elseif ($value !== null) {
                        continue;
                    }

                    throw new \RuntimeException("Block '{$blockType}' is missing required field '{$field}'.");
                }
            }

            if (! empty($content['cta_primary_text']) && empty($content['cta_primary_url'])) {
                throw new \RuntimeException("Block '{$blockType}' has CTA text but no CTA URL.");
            }

            if (! empty($content['cta_secondary_text']) && empty($content['cta_secondary_url'])) {
                throw new \RuntimeException("Block '{$blockType}' has secondary CTA text but no secondary CTA URL.");
            }

            if (! empty($content['cta_text']) && empty($content['cta_url'])) {
                throw new \RuntimeException("Block '{$blockType}' has CTA text but no CTA URL.");
            }

            if ($supportsButtonUrl && ! empty($content['button_text']) && empty($content['button_url'])) {
                throw new \RuntimeException("Block '{$blockType}' has button text but no button URL.");
            }
        }

        $children = is_array($block['children'] ?? null) ? $block['children'] : [];
        if ($children !== []) {
            $supportsChildren = (bool) ($typeConfig['supports_children'] ?? false);
            if (! $supportsChildren) {
                throw new \RuntimeException("Block '{$blockType}' does not support children.");
            }

            $childRules = is_array($governance['supports_children_rules'] ?? null) ? $governance['supports_children_rules'] : [];
            if ($childRules !== []) {
                $maxChildren = is_numeric($childRules['max_children'] ?? null) ? (int) $childRules['max_children'] : null;
                if ($maxChildren !== null && count($children) > $maxChildren) {
                    throw new \RuntimeException("Block '{$blockType}' exceeds max children ({$maxChildren}).");
                }

                $slotKey = is_string($childRules['slot_key'] ?? null) ? $childRules['slot_key'] : '_layout_slot';
                $allowedSlots = is_array($childRules['allowed_slots'] ?? null) ? $childRules['allowed_slots'] : [];
                $requiredSlots = is_array($childRules['required_slots'] ?? null) ? $childRules['required_slots'] : [];

                $seenSlots = [];
                foreach ($children as $child) {
                    if (! is_array($child)) {
                        continue;
                    }

                    $slot = is_array($child['content'] ?? null) ? ($child['content'][$slotKey] ?? '') : '';
                    $slot = is_string($slot) ? $slot : '';
                    if ($slot !== '') {
                        $seenSlots[] = $slot;
                    }

                    if ($allowedSlots !== []) {
                        if ($slot === '' || ! in_array($slot, $allowedSlots, true)) {
                            throw new \RuntimeException("Block '{$blockType}' child slot must be one of: ".implode(', ', $allowedSlots).'.');
                        }
                    }
                }

                if ($requiredSlots !== []) {
                    foreach ($requiredSlots as $requiredSlot) {
                        if (! is_string($requiredSlot) || $requiredSlot === '') {
                            continue;
                        }

                        if (! in_array($requiredSlot, $seenSlots, true)) {
                            throw new \RuntimeException("Block '{$blockType}' is missing required child slot '{$requiredSlot}'.");
                        }
                    }
                }
            }
        }

        foreach ($children as $child) {
            if (! is_array($child)) {
                throw new \RuntimeException("Invalid child block under '{$blockType}'.");
            }

            self::validateBlockTree($pageType, $child, $blockType);
        }
    }
}
