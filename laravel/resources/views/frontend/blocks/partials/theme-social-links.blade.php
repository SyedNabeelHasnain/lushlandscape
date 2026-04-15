@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $links = ($content['source'] ?? 'settings') === 'manual'
        ? ($content['links'] ?? [])
        : $theme->socialLinks();

    $align = match ($content['align'] ?? 'left') {
        'center' => 'justify-center',
        'right' => 'justify-end',
        default => 'justify-start',
    };

    $tone = $content['tone'] ?? 'dark';
    $size = match ($content['size'] ?? 'md') {
        'sm' => 'w-9 h-9 text-[11px]',
        'lg' => 'w-12 h-12 text-sm',
        default => 'w-10 h-10 text-xs',
    };

    $platformIcons = [
        'facebook' => 'facebook',
        'instagram' => 'instagram',
        'twitter' => 'twitter',
        'x' => 'twitter',
        'linkedin' => 'linkedin',
        'youtube' => 'youtube',
        'houzz' => 'home',
        'homestars' => 'badge-check',
        'google' => 'search',
    ];

    $linkClass = $tone === 'light'
        ? 'border border-stone bg-white text-forest hover:bg-forest hover:text-white'
        : 'border border-white/15 bg-white/8 text-white/80 hover:bg-white hover:text-forest';
@endphp

@if(!empty($links))
    <div class="space-y-4">
        @if(!empty($content['heading']))
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] {{ $tone === 'light' ? 'text-text-secondary' : 'text-white/45' }}">
                {{ $content['heading'] }}
            </p>
        @endif
        <div class="flex flex-wrap gap-3 {{ $align }}">
            @foreach($links as $link)
                @if(!empty($link['url']))
                    @php $icon = $platformIcons[strtolower($link['platform'] ?? '')] ?? 'link'; @endphp
                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                        class="{{ $size }} {{ $linkClass }} inline-flex items-center justify-center transition-all duration-300"
                        aria-label="{{ ucfirst($link['platform'] ?? 'Link') }}">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
@endif
