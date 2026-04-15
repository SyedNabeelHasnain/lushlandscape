@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $mode = $content['mode'] ?? 'glass';
    $tone = $content['tone'] ?? 'dark';
    $sticky = (bool) ($content['sticky'] ?? true);
    $compactOnScroll = (bool) ($content['compact_on_scroll'] ?? true);
    $showDivider = (bool) ($content['show_divider'] ?? true);
    $showShadowOnScroll = (bool) ($content['show_shadow_on_scroll'] ?? true);
    $mobileOverlayStyle = $content['mobile_overlay_style'] ?? 'fullscreen';
    $mobileOverlayTone = $content['mobile_overlay_tone'] ?? 'dark';
    $menuLabel = $content['mobile_menu_label'] ?: 'Menu';

    $containerClass = match ($content['content_width'] ?? '7xl') {
        'wide' => 'max-w-[92rem]',
        'full' => 'max-w-full',
        default => 'max-w-7xl',
    };

    $desktopHeightClass = match ($content['desktop_height'] ?? 'standard') {
        'compact' => 'min-h-[4.5rem] lg:min-h-[5rem]',
        'tall' => 'min-h-[5.25rem] lg:min-h-[6.5rem]',
        default => 'min-h-[4.75rem] lg:min-h-[5.75rem]',
    };

    $scrolledHeightClass = match ($content['scrolled_height'] ?? 'compact') {
        'tight' => 'min-h-[4.15rem] lg:min-h-[4.5rem]',
        'standard' => 'min-h-[4.75rem] lg:min-h-[5.5rem]',
        default => 'min-h-[4.4rem] lg:min-h-[4.9rem]',
    };

    $slotSequence = ['left', 'center', 'right', 'mobile'];
    $children = ($block->children ?? collect())->values();
    $slotGroups = collect([
        'left' => collect(),
        'center' => collect(),
        'right' => collect(),
        'mobile' => collect(),
    ]);

    foreach ($children as $index => $child) {
        $slot = data_get($child->content, '_layout_slot') ?: ($slotSequence[$index] ?? 'right');
        if (!$slotGroups->has($slot)) {
            $slot = 'right';
        }

        $slotGroups[$slot] = $slotGroups[$slot]->push($child);
    }

    $normalizeThemeChild = function ($child) {
        $themeChild = clone $child;
        $themeChild->category = 'theme';

        return $themeChild;
    };

    $leftChildren = $slotGroups['left']->map($normalizeThemeChild)->values();
    $centerChildren = $slotGroups['center']->map($normalizeThemeChild)->values();
    $rightChildren = $slotGroups['right']->map($normalizeThemeChild)->values();
    $mobileChildren = $slotGroups['mobile']->isNotEmpty()
        ? $slotGroups['mobile']->map($normalizeThemeChild)
        : $centerChildren->merge($rightChildren)->values();

    $mobileChildren = $mobileChildren->map(function ($child) {
        if ($child->block_type === 'navigation_menu' && data_get($child->content, 'layout') === 'horizontal') {
            $mobileClone = clone $child;
            $mobileClone->content = array_merge($child->content ?? [], ['layout' => 'mobile_overlay']);

            return $mobileClone;
        }

        return $child;
    });

    $hasMobileContent = $mobileChildren->isNotEmpty();
    $headerTextClass = $tone === 'light' ? 'text-ink' : 'text-white';
    $mobileToneClass = $mobileOverlayTone === 'light'
        ? 'bg-white text-ink border-stone/80'
        : 'bg-[rgba(21,56,35,0.96)] text-white border-white/10';
    $mobileToneBorderClass = $mobileOverlayTone === 'light' ? 'border-stone/80' : 'border-white/10';
@endphp

<div
    x-data="themeHeaderShell({ compactOnScroll: @js($compactOnScroll), breakpoint: 1024 })"
    x-on:keydown.escape.window="closeMobile()"
    data-header-mode="{{ $mode }}"
    data-header-tone="{{ $tone }}"
    data-show-divider="{{ $showDivider ? 'true' : 'false' }}"
    data-scroll-shadow="{{ $showShadowOnScroll ? 'true' : 'false' }}"
    data-compact-on-scroll="{{ $compactOnScroll ? 'true' : 'false' }}"
    @class([
        'theme-header-shell w-full z-[110]',
        'sticky top-0' => $sticky,
        'relative' => !$sticky,
    ])
    :class="{ 'is-scrolled': isScrolled, 'is-mobile-open': mobileOpen }"
>
    <div class="{{ $containerClass }} mx-auto px-5 lg:px-10">
        <div
            class="theme-header-shell__inner flex items-center justify-between gap-4 lg:gap-6 transition-[min-height,padding] duration-300 {{ $desktopHeightClass }}"
            :class="isScrolled ? '{{ $scrolledHeightClass }}' : '{{ $desktopHeightClass }}'"
        >
            <div class="flex min-w-0 flex-1 items-center gap-3 lg:gap-6 {{ $headerTextClass }}">
                @foreach($leftChildren as $child)
                    <x-frontend.block-renderer :block="$child" :context="$context" />
                @endforeach
            </div>

            <div class="hidden min-w-0 flex-1 items-center justify-center lg:flex {{ $headerTextClass }}">
                <div class="flex min-w-0 items-center gap-6 xl:gap-10">
                    @foreach($centerChildren as $child)
                        <x-frontend.block-renderer :block="$child" :context="$context" />
                    @endforeach
                </div>
            </div>

            <div class="hidden flex-1 items-center justify-end gap-3 lg:flex {{ $headerTextClass }}">
                @foreach($rightChildren as $child)
                    <x-frontend.block-renderer :block="$child" :context="$context" />
                @endforeach
            </div>

            @if($hasMobileContent)
                <button
                    type="button"
                    class="theme-header-toggle inline-flex items-center gap-2 lg:hidden {{ $headerTextClass }}"
                    x-on:click="toggleMobile()"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="theme-header-mobile-overlay"
                >
                    <span class="text-[10px] font-semibold uppercase tracking-[0.2em]">{{ $menuLabel }}</span>
                    <i data-lucide="menu" class="h-5 w-5" x-show="!mobileOpen"></i>
                    <i data-lucide="x" class="h-5 w-5" x-show="mobileOpen" x-cloak></i>
                </button>
            @endif
        </div>
    </div>

    @if($hasMobileContent)
        <div
            id="theme-header-mobile-overlay"
            x-show="mobileOpen"
            x-cloak
            x-transition.opacity.duration.250ms
            class="theme-mobile-overlay fixed inset-0 z-[115] overflow-y-auto lg:hidden"
        >
            <div @class([
                'min-h-screen',
                $mobileToneClass,
                'mx-4 mt-4 min-h-[calc(100vh-2rem)] rounded-[2rem] border shadow-[0_32px_90px_rgba(15,23,42,0.24)]' => $mobileOverlayStyle === 'sheet',
            ])>
                <div class="mx-auto flex min-h-full w-full max-w-5xl flex-col px-6 pb-10 pt-6 lg:px-10">
                    <div class="flex items-center justify-between gap-4 pb-5 {{ $mobileToneBorderClass }} border-b">
                        <div class="min-w-0 flex-1 {{ $mobileOverlayTone === 'light' ? 'text-ink' : 'text-white' }}">
                            @foreach($leftChildren as $child)
                                <x-frontend.block-renderer :block="$child" :context="$context" />
                            @endforeach
                            @if($leftChildren->isEmpty())
                                <span class="text-sm font-semibold uppercase tracking-[0.22em]">{{ $theme->siteName() }}</span>
                            @endif
                        </div>

                        <button
                            type="button"
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full border {{ $mobileToneBorderClass }}"
                            x-on:click="closeMobile()"
                            aria-label="Close navigation"
                        >
                            <i data-lucide="x" class="h-5 w-5"></i>
                        </button>
                    </div>

                    <div class="flex flex-1 flex-col justify-center py-10">
                        <div class="space-y-8">
                            @foreach($mobileChildren as $child)
                                <div class="border-b {{ $mobileToneBorderClass }} pb-8 last:border-b-0 last:pb-0">
                                    <x-frontend.block-renderer :block="$child" :context="$context" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
