{{--
    Unified Block Renderer
    Renders any block type with its content, data, and styles.
    
    Usage: <x-frontend.block-renderer :block="$block" :context="$context" />
--}}
@props(['block', 'context' => []])

@php
    $sectionMapping = \App\Services\BlockBuilderService::layoutSectionViewMap();
    $blockType = $block->block_type;
    $hasLegacySectionProperty = isset($block->section_key) && filled($block->section_key);
    $hasLegacySectionMarker = !empty(data_get($block->content ?? [], '_legacy_page_section_id'));
    $isExplicitLayoutSection = (bool) ($block->is_layout_section ?? false);
    $isLayout = $hasLegacySectionProperty || $hasLegacySectionMarker || $isExplicitLayoutSection;
    $mapped = $isLayout ? ($sectionMapping[$blockType] ?? null) : null;
    
    // Content data might be stored in settings (legacy DB) or content (new unified block DB)
    $content = $isLayout ? ($block->content ?? $block->settings ?? []) : ($block->content ?? []);
    $content = \App\Services\BlockBuilderService::parseDynamicContent($content, $context);

    
    // For layout sections, we use defaults for category and data resolution
    $category = $isLayout ? 'layout' : $block->category;
    $dataSource = !$isLayout ? $block->getFinalDataSource() : [];
    
    // Resolve data for data blocks (Only if not a layout section, as they handle their own data)
    $data = new \Illuminate\Support\Collection();
    if (!$isLayout && $block->isDataBlock()) {
        $data = \App\Services\BlockBuilderService::resolveBlockData($block, $context);
    }
    
    // Build CSS classes from styles
    $allCssClasses = [];
 
    if (!$isLayout) {
        $contentWidth = $block->getStyle('content_width', 'desktop', 'default');
        $legacyMaxWidth = $block->getStyle('max_width', 'desktop', 'full');
        $activeWidth = $contentWidth !== 'default' ? $contentWidth : $legacyMaxWidth;
        
        $widthMap = [
            'full' => '', 
            '7xl' => 'max-w-7xl', 
            '5xl' => 'max-w-5xl', 
            '3xl' => 'max-w-3xl', 
            'xl' => 'max-w-xl',
            'sm' => 'max-w-sm',
            'premium-narrow' => 'max-w-[880px]'
        ];
        $containerClass = $widthMap[$activeWidth] ?? '';
    } else {
        $containerClass = ''; // Layout sections handle their own containers
    }
    
    // Custom class
    $customClass = $block->getStyle('custom_class', 'desktop', '');
    if ($customClass) $allCssClasses[] = $customClass;

    // Transitions
    $transitionTop = $block->getStyle('transition_top', 'desktop', 'none');
    if ($transitionTop !== 'none') $allCssClasses[] = 'transition-top-' . $transitionTop;
    $transitionBottom = $block->getStyle('transition_bottom', 'desktop', 'none');
    if ($transitionBottom !== 'none') $allCssClasses[] = 'transition-bottom-' . $transitionBottom;
    
    // Surface Presets (CSS Utility Classes)
    $surfacePreset = $block->getStyle('surface_preset', 'desktop', 'none');
    if ($surfacePreset !== 'none') $allCssClasses[] = 'surface-' . $surfacePreset;
    
    // Responsive visibility
    if (!$block->show_on_desktop) $allCssClasses[] = 'lg:hidden';
    if (!$block->show_on_tablet) $allCssClasses[] = 'md:max-lg:hidden';
    if (!$block->show_on_mobile) $allCssClasses[] = 'max-md:hidden';
    
    $allClassesString = implode(' ', array_unique(array_filter($allCssClasses)));
    
    // Custom attributes & Animation
    $htmlId = $block->custom_id ? 'id="'.$block->custom_id.'"' : '';
    $customAttrs = '';
    if (!empty($block->attributes)) {
        foreach ($block->attributes as $k => $v) { $customAttrs .= ' '.$k.'="'.e($v).'"'; }
    }
    $animAttr = $block->animation ? 'data-animate="'.e($block->animation).'"' : '';

    $blockViewKey = str_replace('_', '-', $blockType);
    $blockView = 'frontend.blocks.' . $blockViewKey;
    $partialBlockView = 'frontend.blocks.partials.' . $blockViewKey;

    $mediaIds = [];
    foreach (['media_id', 'bg_media_id', 'before_media_id', 'after_media_id'] as $mediaKey) {
        if (!empty($content[$mediaKey])) $mediaIds[] = (int) $content[$mediaKey];
    }
    foreach (['mobile', 'tablet', 'desktop'] as $styleDevice) {
        $bgImageId = $block->getStyle('bg_image_id', $styleDevice);
        if (!empty($bgImageId)) $mediaIds[] = (int) $bgImageId;
    }
    foreach (['items', 'slides', 'images', 'logos', 'badges', 'cards'] as $mediaArrayKey) {
        foreach ($content[$mediaArrayKey] ?? [] as $item) {
            if (!empty($item['media_id'])) $mediaIds[] = (int) $item['media_id'];
        }
    }
    $mediaLookup = !empty($mediaIds)
        ? \App\Models\MediaAsset::whereIn('id', array_unique($mediaIds))->get()->keyBy('id')
        : collect();

    $styleScopeSource = $block->custom_id ?: ($block->id ?: uniqid('pb-', false));
    $styleScopeId = preg_replace('/[^A-Za-z0-9_-]/', '-', (string) $styleScopeSource);
    $sectionSelector = '[data-block-style-id="'.$styleScopeId.'"]';
    $overlaySelector = '[data-block-style-overlay="'.$styleScopeId.'"]';

    $backgroundImageUrls = [];
    foreach (['mobile', 'tablet', 'desktop'] as $styleDevice) {
        $bgImageId = $block->getStyle('bg_image_id', $styleDevice);
        $backgroundImageUrls[$styleDevice] = $bgImageId ? $mediaLookup->get((int) $bgImageId)?->url : null;
    }

    $overlayColorMap = [
        'none' => null,
        'dark' => '0, 0, 0',
        'light' => '255, 255, 255',
        'forest' => '39, 69, 43',
    ];
    $bgColorMap = [
        'none' => null,
        'white' => 'var(--color-bg-primary, #ffffff)',
        'cream' => 'var(--color-cream, #faf7f2)',
        'gray' => 'var(--color-surface-warm, #f5f5f5)',
        'forest' => 'var(--color-bg-dark, #163823)',
        'dark' => 'var(--color-bg-dark, #111827)',
    ];
    $textColorMap = [
        'default' => null,
        'white' => 'var(--color-text-on-dark, #ffffff)',
        'dark' => 'var(--color-text-on-light, #111111)',
        'forest' => 'var(--color-forest, #1e4a2d)',
    ];
    $textAlignMap = ['left' => 'left', 'center' => 'center', 'right' => 'right'];
    $fontSizeMap = ['default' => null, 'sm' => '0.875rem', 'lg' => '1.125rem', 'xl' => '1.25rem'];
    $fontWeightMap = ['normal' => '400', 'medium' => '500', 'semibold' => '600', 'bold' => '700'];
    $spaceMap = ['none' => '0', 'sm' => '1rem', 'md' => '2rem', 'lg' => '3rem', 'xl' => '4rem'];
    $spacingPresetMap = [
        'none' => [],
        'compact' => [
            'mobile' => ['top' => '2rem', 'bottom' => '2rem', 'left' => '1rem', 'right' => '1rem'],
            'tablet' => ['top' => '2.5rem', 'bottom' => '2.5rem', 'left' => '1.5rem', 'right' => '1.5rem'],
            'desktop' => ['top' => '3rem', 'bottom' => '3rem', 'left' => '2rem', 'right' => '2rem'],
        ],
        'section' => [
            'mobile' => ['top' => '4rem', 'bottom' => '4rem', 'left' => '1.5rem', 'right' => '1.5rem'],
            'tablet' => ['top' => '5rem', 'bottom' => '5rem', 'left' => '2rem', 'right' => '2rem'],
            'desktop' => ['top' => '6rem', 'bottom' => '6rem', 'left' => '2.5rem', 'right' => '2.5rem'],
        ],
        'feature' => [
            'mobile' => ['top' => '4.5rem', 'bottom' => '4.5rem', 'left' => '1.5rem', 'right' => '1.5rem'],
            'tablet' => ['top' => '5.5rem', 'bottom' => '5.5rem', 'left' => '2rem', 'right' => '2rem'],
            'desktop' => ['top' => '7rem', 'bottom' => '7rem', 'left' => '2.5rem', 'right' => '2.5rem'],
        ],
        'hero' => [
            'mobile' => ['top' => '6.5rem', 'bottom' => '5.5rem', 'left' => '1.5rem', 'right' => '1.5rem'],
            'tablet' => ['top' => '7.5rem', 'bottom' => '6rem', 'left' => '2rem', 'right' => '2rem'],
            'desktop' => ['top' => '9rem', 'bottom' => '7rem', 'left' => '3rem', 'right' => '3rem'],
        ],
    ];
    $borderMap = [
        'none' => null,
        'light' => '1px solid rgba(214, 211, 209, 1)',
        'medium' => '2px solid rgba(214, 211, 209, 1)',
    ];
    $shadowMap = [
        'none' => null,
        'sm' => '0 1px 2px rgba(15, 23, 42, 0.08)',
        'md' => '0 10px 25px rgba(15, 23, 42, 0.12)',
        'lg' => '0 22px 50px rgba(15, 23, 42, 0.18)',
    ];
    $surfaceStyleMap = [
        'none' => [],
        'sage-gradient' => [
            'background-color:var(--color-bg-dark, #163823)',
            'background-image:linear-gradient(var(--surface-gradient-start, rgba(21, 56, 35, 0.6)), var(--surface-gradient-end, rgba(21, 56, 35, 0.4)))',
        ],
        'forest-gradient' => [
            'background:linear-gradient(180deg, var(--surface-gradient-deep-start, rgba(21, 56, 35, 0.92)) 0%, var(--surface-gradient-deep-end, rgba(22, 56, 35, 0.78)) 100%)',
        ],
        'cream-panel' => ['background-color:var(--color-cream, #faf7f2)'],
        'glass-light' => [
            'background-color:rgba(255, 255, 255, 0.72)',
            'backdrop-filter:blur(18px)',
            '-webkit-backdrop-filter:blur(18px)',
        ],
        'glass-dark' => [
            'background-color:rgba(21, 56, 35, 0.72)',
            'backdrop-filter:blur(18px)',
            '-webkit-backdrop-filter:blur(18px)',
        ],
        'stone-wash' => ['background-color:var(--color-field-bg, #f8f8f6)'],
    ];
    $glassEffectMap = [
        'none' => null,
        'subtle' => 'backdrop-filter:blur(10px) saturate(1.05);-webkit-backdrop-filter:blur(10px) saturate(1.05);',
        'strong' => 'backdrop-filter:blur(18px) saturate(1.12);-webkit-backdrop-filter:blur(18px) saturate(1.12);',
    ];
    $sectionShellMap = [
        'none' => [],
        'inset-panel' => [
            'border-radius:1.5rem',
            'border:1px solid rgba(218, 221, 216, 0.9)',
        ],
        'luxury-panel' => [
            'border-radius:2rem',
            'border:1px solid rgba(218, 221, 216, 0.72)',
            'box-shadow:0 28px 72px rgba(21, 56, 35, 0.10)',
        ],
        'soft-panel' => [
            'border-radius:1.5rem',
            'border:1px solid rgba(218, 221, 216, 0.82)',
            'box-shadow:0 12px 36px rgba(21, 56, 35, 0.06)',
        ],
    ];
    $dividerStyleMap = [
        'none' => [],
        'top' => ['border-top:1px solid rgba(218, 221, 216, 0.9)'],
        'bottom' => ['border-bottom:1px solid rgba(218, 221, 216, 0.9)'],
        'both' => [
            'border-top:1px solid rgba(218, 221, 216, 0.9)',
            'border-bottom:1px solid rgba(218, 221, 216, 0.9)',
        ],
        'gold-top' => ['border-top:1px solid rgba(164, 113, 72, 0.55)'],
        'gold-bottom' => ['border-bottom:1px solid rgba(164, 113, 72, 0.55)'],
    ];
    $overlayColors = [];
    foreach (['mobile', 'tablet', 'desktop'] as $styleDevice) {
        $overlayTone = $block->getStyle('bg_overlay', $styleDevice, 'none');
        $overlayOpacity = max(0, min(100, (int) $block->getStyle('bg_overlay_opacity', $styleDevice, 50)));
        $overlayRgb = $overlayColorMap[$overlayTone] ?? null;
        $overlayColors[$styleDevice] = $overlayRgb ? sprintf('rgba(%s, %.2F)', $overlayRgb, $overlayOpacity / 100) : 'transparent';
    }

    $hasBackgroundImage = collect($backgroundImageUrls)->filter()->isNotEmpty();
    $hasOverlay = collect($overlayColors)->contains(fn ($color) => $color !== 'transparent');
    $styleRules = [];
    $appendStyleRule = function (string $selector, string $declarations, ?string $minWidth = null) use (&$styleRules) {
        $rule = $selector.'{'.$declarations.'}';
        $styleRules[] = $minWidth ? '@media (min-width: '.$minWidth.'){'.$rule.'}' : $rule;
    };

    if ($hasBackgroundImage) {
        $appendStyleRule(
            $sectionSelector,
            'background-position:center;background-repeat:no-repeat;background-size:cover;background-image:url('.json_encode($backgroundImageUrls['mobile']).');'
        );

        if (!empty($backgroundImageUrls['tablet'])) {
            $appendStyleRule(
                $sectionSelector,
                'background-image:url('.json_encode($backgroundImageUrls['tablet']).');',
                '768px'
            );
        }

        if (!empty($backgroundImageUrls['desktop'])) {
            $appendStyleRule(
                $sectionSelector,
                'background-image:url('.json_encode($backgroundImageUrls['desktop']).');',
                '1024px'
            );
        }
    }

    if ($hasOverlay) {
        $appendStyleRule($overlaySelector, 'background-color:'.$overlayColors['mobile'].';');
        $appendStyleRule($overlaySelector, 'background-color:'.$overlayColors['tablet'].';', '768px');
        $appendStyleRule($overlaySelector, 'background-color:'.$overlayColors['desktop'].';', '1024px');
    }

    $inheritSelector = $sectionSelector.' :where(h1,h2,h3,h4,h5,h6,p,li,a,span,strong,em,small,blockquote)';
    foreach (['mobile' => null, 'tablet' => '768px', 'desktop' => '1024px'] as $styleDevice => $minWidth) {
        $declarations = [];

        $surfaceStyle = $block->getStyle('surface_style', $styleDevice, 'none');
        if ($surfaceStyle !== 'none') {
            $surfaceDeclarations = $surfaceStyleMap[$surfaceStyle] ?? [];
            if ($hasBackgroundImage) {
                $surfaceDeclarations = array_values(array_filter(
                    $surfaceDeclarations,
                    fn (string $declaration) => !str_starts_with($declaration, 'background:')
                        && !str_starts_with($declaration, 'background-image:')
                ));
            }
            $declarations = array_merge($declarations, $surfaceDeclarations);
        }

        $glassEffect = $block->getStyle('glass_effect', $styleDevice, 'none');
        if (!empty($glassEffectMap[$glassEffect])) {
            $declarations[] = $glassEffectMap[$glassEffect];
        }

        $bgColor = $block->getStyle('bg_color', $styleDevice, 'none');
        if (!empty($bgColorMap[$bgColor])) {
            $declarations[] = 'background-color:'.$bgColorMap[$bgColor];
        }

        $textAlign = $block->getStyle('text_align', $styleDevice, 'left');
        if (!empty($textAlignMap[$textAlign])) {
            $declarations[] = 'text-align:'.$textAlignMap[$textAlign];
            $appendStyleRule($inheritSelector, 'text-align:inherit;', $minWidth);
        }

        foreach (['top', 'bottom'] as $side) {
            $spacingPreset = $block->getStyle('spacing_preset', $styleDevice, 'section');
            $presetDeclaration = data_get($spacingPresetMap, $spacingPreset.'.'.$styleDevice.'.'.$side);
            if ($presetDeclaration) {
                $declarations[] = 'padding-'.$side.':'.$presetDeclaration;
            }
        }
        
        foreach (['top', 'bottom', 'left', 'right'] as $side) {
            $paddingValue = $block->getStyle('padding_'.$side, $styleDevice);
            if (isset($spaceMap[$paddingValue])) {
                $declarations[] = 'padding-'.$side.':'.$spaceMap[$paddingValue];
            }

            $marginValue = $block->getStyle('margin_'.$side, $styleDevice);
            if (isset($spaceMap[$marginValue])) {
                $declarations[] = 'margin-'.$side.':'.$spaceMap[$marginValue];
            }
        }

        $textColor = $block->getStyle('text_color', $styleDevice, 'default');
        if (!empty($textColorMap[$textColor])) {
            $declarations[] = 'color:'.$textColorMap[$textColor];
            $appendStyleRule($inheritSelector, 'color:inherit;', $minWidth);
        } elseif (in_array($surfaceStyle, ['sage-gradient', 'forest-gradient', 'glass-dark'], true)) {
            $declarations[] = 'color:var(--color-text-on-dark, #ffffff)';
            $appendStyleRule($inheritSelector, 'color:inherit;', $minWidth);
        }

        $fontSize = $block->getStyle('font_size', $styleDevice, 'default');
        if (!empty($fontSizeMap[$fontSize])) {
            $declarations[] = 'font-size:'.$fontSizeMap[$fontSize];
            $appendStyleRule($inheritSelector, 'font-size:inherit;', $minWidth);
        }

        $fontWeight = $block->getStyle('font_weight', $styleDevice, 'normal');
        if (!empty($fontWeightMap[$fontWeight])) {
            $declarations[] = 'font-weight:'.$fontWeightMap[$fontWeight];
            $appendStyleRule($inheritSelector, 'font-weight:inherit;', $minWidth);
        }

        $sectionShell = $block->getStyle('section_shell', $styleDevice, 'none');
        if ($sectionShell !== 'none') {
            $declarations = array_merge($declarations, $sectionShellMap[$sectionShell] ?? []);
        }

        $dividerStyle = $block->getStyle('divider_style', $styleDevice, 'none');
        if ($dividerStyle !== 'none') {
            $declarations = array_merge($declarations, $dividerStyleMap[$dividerStyle] ?? []);
        }

        if ($styleDevice === 'desktop') {
            if ($block->getStyle('rounded', 'desktop', false)) {
                $declarations[] = 'border-radius:1.5rem';
            }

            $border = $block->getStyle('border', 'desktop', 'none');
            if (!empty($borderMap[$border])) {
                $declarations[] = 'border:'.$borderMap[$border];
            }

            $shadow = $block->getStyle('shadow', 'desktop', 'none');
            if (!empty($shadowMap[$shadow])) {
                $declarations[] = 'box-shadow:'.$shadowMap[$shadow];
            }

            $overflow = $block->getStyle('overflow', 'desktop', 'visible');
            if ($overflow !== 'visible') {
                $declarations[] = 'overflow:'.$overflow;
            }

            $zIndex = (int) $block->getStyle('z_index', 'desktop', 0);
            if ($zIndex !== 0) {
                $declarations[] = 'z-index:'.$zIndex;
            }
        }

        if (!empty($declarations)) {
            $appendStyleRule($sectionSelector, implode(';', $declarations).';', $minWidth);
        }
    }

    if ($hasBackgroundImage) {
        $allCssClasses[] = 'bg-cover';
        $allCssClasses[] = 'bg-center';
        $allCssClasses[] = 'bg-no-repeat';
    }

    if ($hasBackgroundImage || $hasOverlay) {
        $allCssClasses[] = 'relative';
        $allCssClasses[] = 'isolate';
    }

    $contentLayerClass = $hasOverlay ? 'relative z-10' : '';
    $inlineStyleCss = implode('', $styleRules);
@endphp

@if($isLayout)
    {{-- Render Legacy Layout Section --}}
    @if($mapped && $block->is_enabled)
        <section {{ $htmlId }} {!! $customAttrs !!} {!! $animAttr !!} data-block-style-id="{{ $styleScopeId }}" class="{{ $allClassesString }}">
            @if($inlineStyleCss !== '')
            <style>{!! $inlineStyleCss !!}</style>
            @endif
            @if($hasOverlay)
            <div data-block-style-overlay="{{ $styleScopeId }}" class="pointer-events-none absolute inset-0 z-0"></div>
            @endif
            <div @class([$contentLayerClass => $contentLayerClass !== ''])>
            @if($mapped['type'] === 'view')
                @include($mapped['name'], [
                    'section' => ['settings' => $content, 'id' => $block->id, 'key' => $blockType],
                    'service' => $context['service'] ?? null,
                    'city' => $context['city'] ?? null,
                    'page' => $context['page'] ?? null,
                    'servicePages' => $context['servicePages'] ?? collect(),
                    'cityPages' => $context['cityPages'] ?? collect(),
                    'context' => $context
                ])
            @else
                <x-dynamic-component 
                    :component="$mapped['name']" 
                    :settings="$content" 
                    :context="$context" 
                />
            @endif
            </div>
        </section>
    @endif
@else
    {{-- Render Standard Content Block --}}
    @if($block->is_enabled)
    <section {{ $htmlId }} {!! $customAttrs !!} {!! $animAttr !!} data-block-style-id="{{ $styleScopeId }}" class="{{ $allClassesString }}">
        @if($inlineStyleCss !== '')
        <style>{!! $inlineStyleCss !!}</style>
        @endif
        @if($hasOverlay)
        <div data-block-style-overlay="{{ $styleScopeId }}" class="pointer-events-none absolute inset-0 z-0"></div>
        @endif
        @if($containerClass)
        <div class="{{ trim($containerClass.' '.$contentLayerClass) }} mx-auto px-6 lg:px-12">
        @endif

        <div @class([$contentLayerClass => !$containerClass && $contentLayerClass !== ''])>
            @if(view()->exists($blockView))
                @include($blockView, [
                    'block' => $block,
                    'content' => $content,
                    'data' => $data,
                    'context' => $context,
                    'mediaLookup' => $mediaLookup,
                    'section' => ['settings' => $content],
                ])
            @else
                @includeFirst([$partialBlockView, 'frontend.blocks.partials.rich-text'], [
                    'block' => $block,
                    'content' => $content,
                    'data' => $data,
                    'context' => $context,
                    'section' => ['settings' => $content],
                ])
            @endif
        </div>
        
        @if($containerClass)
        </div>
        @endif
    </section>
    @endif
@endif
