@php
    $tone = $content['tone'] ?? 'light';
    $ratio = $content['image_ratio'] ?? '4:3';
    $imageUrl = $content['image_url'] ?? '';
    $eyebrow = $content['eyebrow'] ?? '';
    $title = $content['title'] ?? '';
    $subtitle = $content['subtitle'] ?? '';
    $showCta = (bool) ($content['show_cta'] ?? false);
    $ctaText = $content['cta_text'] ?? 'View';
    $ctaUrl = $content['cta_url'] ?? '';

    $surface = match ($tone) {
        'cream' => 'bg-cream border-stone',
        'forest' => 'bg-forest border-forest text-white',
        'dark' => 'bg-luxury-dark border-white/10 text-white',
        default => 'bg-white border-stone',
    };

    $metaText = match ($tone) {
        'forest', 'dark' => 'text-white/70',
        default => 'text-text-secondary',
    };

    $titleText = match ($tone) {
        'forest', 'dark' => 'text-white',
        default => 'text-text',
    };

    $bodyText = match ($tone) {
        'forest', 'dark' => 'text-white/80',
        default => 'text-text-secondary',
    };

    $ratioClass = match ($ratio) {
        '16:9' => 'aspect-[16/9]',
        '1:1' => 'aspect-square',
        '3:2' => 'aspect-[3/2]',
        default => 'aspect-[4/3]',
    };
@endphp

<div class="h-full overflow-hidden border {{ $surface }} transition-shadow duration-300 group-hover:shadow-luxury">
    @if($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full {{ $ratioClass }} object-cover" loading="lazy" decoding="async">
    @endif

    <div class="p-5">
        @if($eyebrow)
            <div class="text-[11px] font-semibold uppercase tracking-[0.14em] {{ $metaText }}">{{ $eyebrow }}</div>
        @endif

        @if($title)
            <h3 class="mt-2 text-base font-semibold leading-snug {{ $titleText }} line-clamp-2">{{ $title }}</h3>
        @endif

        @if($subtitle)
            <p class="mt-2 text-sm leading-relaxed {{ $bodyText }} line-clamp-3">{{ $subtitle }}</p>
        @endif

        @if($showCta && $ctaUrl)
            <div class="mt-4">
                <a href="{{ $ctaUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold {{ $tone === 'forest' || $tone === 'dark' ? 'text-white' : 'text-forest' }} hover:underline">
                    {{ $ctaText }}
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        @endif
    </div>
</div>

