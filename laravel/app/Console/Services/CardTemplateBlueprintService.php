<?php

namespace App\Console\Services;

use App\Models\CardTemplate;
use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\DB;

class CardTemplateBlueprintService
{
    public const TEMPLATE_DEFINITIONS = [
        [
            'name' => 'Card — Service',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => 'Service',
                        'title' => '{service.name}',
                        'subtitle' => '{service.short_description}',
                        'tone' => 'light',
                        'image_ratio' => '4:3',
                        'show_cta' => false,
                        'cta_text' => 'View',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
        [
            'name' => 'Card — Service Category',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => 'Category',
                        'title' => '{category.name}',
                        'subtitle' => '{category.short_description}',
                        'tone' => 'light',
                        'image_ratio' => '4:3',
                        'show_cta' => false,
                        'cta_text' => 'Explore',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
        [
            'name' => 'Card — City',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => 'Service Area',
                        'title' => '{city.name}',
                        'subtitle' => 'Landscaping services in {city.name}.',
                        'tone' => 'light',
                        'image_ratio' => '4:3',
                        'show_cta' => false,
                        'cta_text' => 'View',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
        [
            'name' => 'Card — Project',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => '{project.project_type}',
                        'title' => '{project.title}',
                        'subtitle' => '{project.description}',
                        'tone' => 'light',
                        'image_ratio' => '4:3',
                        'show_cta' => false,
                        'cta_text' => 'View',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
        [
            'name' => 'Card — Blog Post',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => 'Article',
                        'title' => '{post.title}',
                        'subtitle' => '{post.excerpt}',
                        'tone' => 'light',
                        'image_ratio' => '16:9',
                        'show_cta' => false,
                        'cta_text' => 'Read',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
        [
            'name' => 'Card — Related Content',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => '{item.type}',
                        'title' => '{item.title}',
                        'subtitle' => '{item.excerpt}',
                        'tone' => 'light',
                        'image_ratio' => '4:3',
                        'show_cta' => false,
                        'cta_text' => 'View',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
        [
            'name' => 'Card — Authority / Value',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => 'Value',
                        'title' => '{item.title}',
                        'subtitle' => '{item.subtitle}',
                        'tone' => 'cream',
                        'image_ratio' => '1:1',
                        'show_cta' => false,
                        'cta_text' => 'View',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
        [
            'name' => 'Card — Process Step',
            'blocks' => [
                [
                    'block_type' => 'template_card_shell',
                    'category' => 'content',
                    'is_enabled' => true,
                    'content' => [
                        'image_url' => '{item.image_url}',
                        'eyebrow' => 'Process',
                        'title' => '{item.title}',
                        'subtitle' => '{item.desc}',
                        'tone' => 'light',
                        'image_ratio' => '1:1',
                        'show_cta' => false,
                        'cta_text' => 'View',
                        'cta_url' => '{item.url}',
                    ],
                ],
            ],
        ],
    ];

    public function scaffold(bool $activate = false): array
    {
        return DB::transaction(function () use ($activate) {
            $templates = [];

            foreach (self::TEMPLATE_DEFINITIONS as $definition) {
                $templates[] = $this->upsertTemplate(
                    (string) $definition['name'],
                    (array) ($definition['blocks'] ?? []),
                    $activate,
                );
            }

            return $templates;
        });
    }

    private function upsertTemplate(string $name, array $blocks, bool $activate): CardTemplate
    {
        $template = CardTemplate::query()->firstOrNew(['name' => $name]);

        $template->fill([
            'is_active' => $activate ? true : ($template->exists ? (bool) $template->is_active : false),
        ]);
        $template->save();

        BlockBuilderService::saveUnifiedBlocks('template_card', $template->id, $blocks);

        return $template->fresh();
    }
}
