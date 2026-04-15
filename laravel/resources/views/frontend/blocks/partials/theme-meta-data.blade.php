@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $key = $content['meta_key'] ?? 'phone';
    $display = $content['display'] ?? 'inline';
    $tone = $content['tone'] ?? 'inherit';
    $icon = $content['icon'] ?? 'auto';
    $prefix = trim((string) ($content['prefix'] ?? ''));

    $value = \App\Models\Setting::get($key, '');
    if ($key === 'footer_copyright_text') {
        $value = $theme->copyrightText();
    } elseif ($key === 'google_rating') {
        $count = $theme->reviewCount();
        $value = trim($theme->ratingValue().' '.($count ? "({$count})" : ''));
    }

    $toneClass = match ($tone) {
        'light' => 'text-white',
        'dark' => 'text-ink',
        'accent' => 'text-accent',
        default => '',
    };

    $wrapperClass = match ($display) {
        'paragraph' => 'block max-w-md space-y-2',
        'stacked' => 'flex flex-col gap-1',
        'pill' => 'inline-flex items-center gap-2 rounded-full border border-current/15 px-4 py-2',
        default => 'inline-flex items-center gap-2',
    };

    $escapedPrefix = e($prefix);
    $labelMarkup = match (true) {
        $prefix === '' => '',
        $display === 'paragraph' => '<span class="block text-[10px] uppercase tracking-[0.2em] font-semibold opacity-65">'.$escapedPrefix.'</span>',
        default => '<span class="text-[10px] uppercase tracking-[0.18em] font-semibold opacity-60">'.$escapedPrefix.'</span>',
    };

    $iconMap = [
        'phone' => 'phone',
        'email' => 'mail',
        'address' => 'map-pin',
        'google_rating' => 'star',
        'business_hours_weekday' => 'clock',
        'business_hours_weekend' => 'clock',
    ];
    $resolvedIcon = $icon === 'auto' ? ($iconMap[$key] ?? null) : ($icon === 'none' ? null : $icon);
    $showIcon = $resolvedIcon && $display !== 'paragraph';
    $escapedValue = e((string) $value);
    $interactiveClass = $display === 'paragraph'
        ? 'transition hover:opacity-80 text-sm leading-relaxed'
        : 'transition hover:opacity-80';

    $contentMarkup = match ($key) {
        'phone' => '<a href="tel:'.e($theme->phoneClean()).'" class="'.$interactiveClass.'">'.$escapedValue.'</a>',
        'email' => '<a href="mailto:'.$escapedValue.'" class="'.$interactiveClass.'">'.$escapedValue.'</a>',
        default => $display === 'paragraph'
            ? '<span class="block text-sm leading-relaxed opacity-90">'.$escapedValue.'</span>'
            : '<span>'.$escapedValue.'</span>',
    };
@endphp

@if($value !== '')
    <div class="theme-meta-block {{ $toneClass }} {{ $wrapperClass }}">
        @if($showIcon)
            <i data-lucide="{{ $resolvedIcon }}" class="w-4 h-4 shrink-0"></i>
        @endif
        @if($labelMarkup !== '')
            {!! $labelMarkup !!}
        @endif
        {!! $contentMarkup !!}
    </div>
@endif
