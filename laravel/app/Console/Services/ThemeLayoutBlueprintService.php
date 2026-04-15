<?php

namespace App\Console\Services;

use App\Services\BlockBuilderService;
use App\Services\ThemePresentationService;
use App\Models\ThemeLayout;
use Illuminate\Support\Facades\DB;

class ThemeLayoutBlueprintService
{
    public const HEADER_LAYOUT_NAME = 'Master Site Header';
    public const FOOTER_LAYOUT_NAME = 'Master Site Footer';
    public const HEADER_LAYOUT_VARIANTS = [
        ['name' => self::HEADER_LAYOUT_NAME, 'mode' => 'glass', 'tone' => 'dark'],
        ['name' => 'Master Site Header — Transparent (Dark)', 'mode' => 'transparent', 'tone' => 'dark'],
        ['name' => 'Master Site Header — Solid (Light)', 'mode' => 'solid', 'tone' => 'light'],
        ['name' => 'Master Site Header — Solid (Dark)', 'mode' => 'solid', 'tone' => 'dark'],
    ];

    public function __construct(
        private readonly ThemePresentationService $themePresentation,
    ) {
    }

    /**
     * Scaffold the default header/footer theme layouts as reusable builder drafts.
     *
     * @return array{header: \App\Models\ThemeLayout, footer: \App\Models\ThemeLayout}
     */
    public function scaffold(bool $activate = false): array
    {
        return DB::transaction(function () use ($activate) {
            $primaryHeader = null;
            foreach (self::HEADER_LAYOUT_VARIANTS as $variant) {
                $layout = $this->upsertLayout(
                    (string) $variant['name'],
                    'header',
                    $this->headerBlocks((string) $variant['mode'], (string) $variant['tone']),
                    $activate && $variant['name'] === self::HEADER_LAYOUT_NAME,
                );

                if ($variant['name'] === self::HEADER_LAYOUT_NAME) {
                    $primaryHeader = $layout;
                }
            }

            $header = $primaryHeader ?? $this->upsertLayout(
                self::HEADER_LAYOUT_NAME,
                'header',
                $this->headerBlocks('glass', 'dark'),
                $activate,
            );

            $footer = $this->upsertLayout(
                self::FOOTER_LAYOUT_NAME,
                'footer',
                $this->footerBlocks(),
                $activate,
            );

            return ['header' => $header, 'footer' => $footer];
        });
    }

    private function upsertLayout(string $name, string $type, array $blocks, bool $activate): ThemeLayout
    {
        $layout = ThemeLayout::query()->firstOrNew([
            'name' => $name,
            'type' => $type,
        ]);

        $layout->fill([
            'conditions' => [],
            'is_active' => $activate ? true : ($layout->exists ? (bool) $layout->is_active : false),
        ]);
        $layout->save();

        BlockBuilderService::saveUnifiedBlocks('theme_layout', $layout->id, $blocks);

        return $layout->fresh();
    }

    private function headerBlocks(string $mode, string $tone): array
    {
        $ctaDefaults = (array) (config('blocks.types.theme_cta_group.defaults') ?? []);
        $primaryText = (string) ($ctaDefaults['primary_text'] ?? 'Book a Consultation');
        $primaryUrl = (string) ($ctaDefaults['primary_url'] ?? '/contact');

        return [
            $this->themeBlock(
                'theme_header_shell',
                [
                    'mode' => $mode,
                    'tone' => $tone,
                    'sticky' => true,
                    'compact_on_scroll' => true,
                    'show_divider' => true,
                    'show_shadow_on_scroll' => true,
                    'desktop_height' => 'tall',
                    'scrolled_height' => 'compact',
                    'content_width' => '7xl',
                    'mobile_overlay_style' => 'fullscreen',
                    'mobile_overlay_tone' => $tone,
                    'mobile_menu_label' => 'Menu',
                ],
                [],
                [
                    $this->themeChild(
                        'site_logo',
                        [
                            'source' => 'header_desktop',
                            'size' => 'xl',
                            'tone' => 'auto',
                            'show_tagline' => false,
                            '_layout_slot' => 'left',
                        ],
                    ),
                    $this->themeChild(
                        'navigation_menu',
                        [
                            'layout' => 'horizontal',
                            'tone' => $tone,
                            'style' => 'luxury',
                            'show_services' => true,
                            'show_locations' => true,
                            'show_portfolio' => true,
                            'show_about' => true,
                            'show_contact' => true,
                            'service_limit' => 6,
                            'city_limit' => 8,
                            '_layout_slot' => 'center',
                        ],
                    ),
                    $this->themeChild(
                        'theme_cta_group',
                        [
                            'align' => 'right',
                            'tone' => $tone,
                            'primary_text' => $primaryText,
                            'primary_url' => $primaryUrl,
                            'primary_style' => 'ghost',
                            'secondary_text' => '',
                            'secondary_url' => '',
                            'secondary_style' => 'white',
                            '_layout_slot' => 'right',
                        ],
                    ),
                ],
            ),
        ];
    }

    private function footerBlocks(): array
    {
        return [
            $this->themeBlock(
                'theme_newsletter_panel',
                [
                    'eyebrow' => 'Stay Updated',
                    'heading' => '',
                    'description' => '',
                    'tone' => 'dark',
                    'layout' => 'split',
                    'placeholder' => 'your@email.com',
                    'button_text' => 'Subscribe',
                ],
            ),
            $this->layoutBlock(
                'two_column',
                [
                    'ratio' => '1:2',
                    'gap' => 'lg',
                ],
                [
                    'desktop' => [
                        ...BlockBuilderService::styleDefaults()['desktop'],
                        'surface_style' => 'forest-gradient',
                        'spacing_preset' => 'feature',
                        'margin_bottom' => 'none',
                    ],
                    'tablet' => [],
                    'mobile' => [],
                ],
                [
                    $this->themeChild(
                        'site_logo',
                        [
                            'source' => 'footer',
                            'size' => 'xl',
                            'tone' => 'light',
                            'show_tagline' => false,
                            '_layout_slot' => 'left',
                        ],
                    ),
                    $this->themeChild(
                        'theme_meta_data',
                        [
                            'meta_key' => 'footer_tagline',
                            'display' => 'paragraph',
                            'tone' => 'light',
                            'icon' => 'none',
                            'prefix' => '',
                            '_layout_slot' => 'left',
                        ],
                    ),
                    $this->themeChild(
                        'theme_contact_strip',
                        [
                            'variant' => 'compact',
                            'tone' => 'dark',
                            'show_phone' => true,
                            'show_email' => false,
                            'show_rating' => true,
                            'show_hours' => false,
                            '_layout_slot' => 'left',
                        ],
                    ),
                    $this->themeChild(
                        'theme_social_links',
                        [
                            'heading' => 'Follow Us',
                            'source' => 'settings',
                            'align' => 'left',
                            'tone' => 'dark',
                            'size' => 'md',
                            'links' => [],
                            '_layout_slot' => 'left',
                        ],
                    ),
                    $this->themeChild(
                        'theme_footer_columns',
                        [
                            'source' => 'settings',
                            'show_services' => true,
                            'show_locations' => true,
                            'show_company' => true,
                            'services_heading' => 'Expertise',
                            'locations_heading' => 'Locations',
                            'company_heading' => 'Company',
                            'show_call_panel' => false,
                            '_layout_slot' => 'right',
                        ],
                    ),
                ],
            ),
            $this->themeBlock(
                'theme_legal_bar',
                [
                    'tone' => 'dark',
                    'show_copyright' => true,
                    'links_source' => 'settings',
                    'custom_links_text' => '',
                ],
                [
                    'desktop' => [
                        ...config('blocks.theme_style_defaults.desktop', []),
                        'surface_style' => 'forest-gradient',
                        'spacing_preset' => 'compact',
                    ],
                    'tablet' => [],
                    'mobile' => [],
                ],
            ),
        ];
    }

    private function themeBlock(string $type, array $content = [], array $styles = [], array $children = []): array
    {
        return [
            'block_type' => $type,
            'category' => 'theme',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => $content,
            'styles' => $styles !== [] ? $styles : config('blocks.theme_style_defaults', []),
            'children' => $children,
        ];
    }

    private function themeChild(string $type, array $content = [], array $styles = []): array
    {
        return $this->themeBlock($type, $content, $styles);
    }

    private function layoutBlock(string $type, array $content = [], array $styles = [], array $children = []): array
    {
        return [
            'block_type' => $type,
            'category' => 'layout',
            'is_enabled' => true,
            'show_on_desktop' => true,
            'show_on_tablet' => true,
            'show_on_mobile' => true,
            'content' => $content,
            'styles' => $styles !== [] ? $styles : BlockBuilderService::styleDefaults(),
            'children' => $children,
        ];
    }
}
