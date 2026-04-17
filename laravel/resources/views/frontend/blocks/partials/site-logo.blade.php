@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $siteName = $theme->siteName();
    $source = $content['source'] ?? 'auto';
    $logo = $theme->logo($source);
    $size = $content['size'] ?? 'lg';
    $tone = $content['tone'] ?? 'auto';
    $showTagline = (bool) ($content['show_tagline'] ?? false);
    $tagline = $content['tagline'] ?? $theme->tagline();

    $logoSizeClass = match ($size) {
        'sm' => 'h-8',
        'md' => 'h-10',
        'xl' => 'h-14 lg:h-16',
        default => 'h-11 lg:h-12',
    };

    $textToneClass = match ($tone) {
        'light' => 'text-white',
        'muted' => 'text-white/75',
        'auto' => 'text-current',
        default => 'text-ink',
    };

    $taglineClass = match ($tone) {
        'light' => 'text-white/55',
        'muted' => 'text-white/45',
        'auto' => 'text-current opacity-70',
        default => 'text-text-secondary',
    };

    $logoToneClass = match ($tone) {
        'light' => 'brightness-0 invert',
        'muted' => 'brightness-0 invert opacity-80',
        default => '',
    };
@endphp

<a
    href="{{ url('/') }}"
    class="theme-header-logo inline-flex items-center gap-3"
    aria-label="{{ $siteName }} Home"
    data-header-logo
    data-logo-tone="{{ $tone }}"
>
    @if($logo)
        <img
            src="{{ $logo->url }}"
            alt="{{ $logo->default_alt_text ?? $siteName }}"
            class="{{ $logoSizeClass }} w-auto object-contain transition-all duration-300 {{ $logoToneClass }}"
            data-header-logo-image
            width="{{ $logo->width ?? '200' }}"
            height="{{ $logo->height ?? '48' }}"
        >
    @endif

    @if(!$logo || $showTagline)
        <span class="flex flex-col min-w-0">
            @if(!$logo)
                <span class="text-xl lg:text-2xl font-heading font-bold tracking-tight {{ $textToneClass }}">
                    {{ $siteName }}
                </span>
            @endif
            @if($showTagline)
                <span class="text-[10px] uppercase tracking-[0.22em] font-semibold {{ $taglineClass }}" data-header-logo-tagline>
                    {{ $tagline }}
                </span>
            @endif
        </span>
    @endif
</a>
