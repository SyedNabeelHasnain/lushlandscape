@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $tone = $content['tone'] ?? 'dark';
    $showCopyright = (bool) ($content['show_copyright'] ?? true);
    $linksSource = $content['links_source'] ?? 'settings';
    $customLines = preg_split('/\r\n|\r|\n/', (string) ($content['custom_links_text'] ?? '')) ?: [];

    $links = match ($linksSource) {
        'default' => [
            ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
            ['label' => 'Terms & Conditions', 'url' => '/terms'],
            ['label' => 'Sitemap', 'url' => '/sitemap.xml'],
        ],
        'custom' => collect($customLines)
            ->map(function (string $line) {
                [$label, $url] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
                return ($label !== '' && $url !== '') ? ['label' => $label, 'url' => $url] : null;
            })
            ->filter()
            ->values()
            ->all(),
        default => $theme->bottomLinks(),
    };

    $toneClass = $tone === 'light' ? 'text-text-secondary' : 'text-white/55';
@endphp

<div class="flex flex-col gap-4 border-t {{ $tone === 'light' ? 'border-stone' : 'border-white/10' }} pt-6 md:flex-row md:items-center md:justify-between {{ $toneClass }}">
    @if($showCopyright)
        <p class="text-[11px] tracking-wide">{{ $theme->copyrightText() }}</p>
    @endif

    @if(!empty($links))
        <div class="flex flex-wrap gap-5 text-[11px] tracking-wide">
            @foreach($links as $link)
                <a href="{{ $link['url'] ?? '#' }}" class="transition hover:opacity-90">
                    {{ $link['label'] ?? '' }}
                </a>
            @endforeach
        </div>
    @endif
</div>
