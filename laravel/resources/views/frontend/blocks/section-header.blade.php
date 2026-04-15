@php
    $align = ($content['align'] ?? 'center') === 'center' ? 'text-center' : 'text-left';
    $variant = $content['variant'] ?? 'editorial';
    $tone = $content['tone'] ?? 'forest';
    $ctaText = $content['cta_text'] ?? '';
    $ctaUrl = $content['cta_url'] ?? '';
    $widthClass = match ($content['width'] ?? 'lg') {
        'md' => 'max-w-3xl',
        'xl' => 'max-w-7xl',
        default => 'max-w-5xl',
    };
    $headingClass = match ($tone) {
        'dark' => 'text-ink',
        'light' => 'text-white',
        default => 'text-forest',
    };
    $subClass = match ($tone) {
        'dark' => 'text-text-secondary',
        'light' => 'text-white/72',
        default => 'text-text-secondary',
    };
    $tagClass = match ($tone) {
        'light' => 'text-white border border-white/20 bg-white/8',
        'dark' => 'text-ink border border-stone bg-cream',
        default => 'text-forest bg-forest/6 border border-forest/10',
    };
@endphp
@if(!empty($content['heading']))
<div class="{{ $widthClass }} mx-auto px-6 lg:px-12 {{ $variant === 'compact' ? 'py-5' : 'py-8 lg:py-10' }} reveal">
    @if($variant === 'split' && !empty($content['subtitle']))
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1.15fr)_minmax(0,0.85fr)] lg:items-end">
            <div class="{{ $align }}">
                @if(!empty($content['tag']))
                    <span class="inline-flex items-center px-4 py-2 mb-5 text-[10px] font-bold uppercase tracking-[0.18em] {{ $tagClass }}">{{ $content['tag'] }}</span>
                @endif
                <h2 class="text-h2 font-heading font-bold tracking-tight {{ $headingClass }}">{{ $content['heading'] }}</h2>
                @if(!empty($content['show_line']))
                    <div class="mt-6 h-px w-24 {{ ($content['align'] ?? 'center') === 'center' ? 'mx-auto' : '' }} {{ $tone === 'light' ? 'bg-white/25' : 'bg-accent/40' }}"></div>
                @endif
            </div>
            <p class="text-body-lg leading-relaxed {{ $subClass }} {{ ($content['align'] ?? 'center') === 'center' ? 'lg:text-center lg:mx-auto' : '' }}">
                {{ $content['subtitle'] }}
            </p>
        </div>
    @else
        <div class="{{ $align }} {{ ($ctaText && $ctaUrl && ($content['align'] ?? 'center') !== 'center') ? 'lg:flex lg:items-end lg:justify-between lg:gap-8' : '' }}">
            @if($ctaText && $ctaUrl && ($content['align'] ?? 'center') !== 'center')
                <div class="order-1">
            @endif
            @if(!empty($content['tag']))
                <span class="inline-flex items-center px-4 py-2 mb-5 text-[10px] font-bold uppercase tracking-[0.18em] {{ $tagClass }}">{{ $content['tag'] }}</span>
            @endif
            <h2 class="{{ $variant === 'compact' ? 'text-h3' : 'text-h2' }} font-heading font-bold tracking-tight {{ $headingClass }}">{{ $content['heading'] }}</h2>
            @if(!empty($content['show_line']))
                <div class="mt-6 h-px w-24 {{ ($content['align'] ?? 'center') === 'center' ? 'mx-auto' : '' }} {{ $tone === 'light' ? 'bg-white/25' : 'bg-accent/40' }}"></div>
            @endif
            @if(!empty($content['subtitle']))
                <p class="mt-5 text-body-lg {{ $subClass }} max-w-2xl {{ ($content['align'] ?? 'center') === 'center' ? 'mx-auto' : '' }} leading-relaxed">{{ $content['subtitle'] }}</p>
            @endif
            @if($ctaText && $ctaUrl && ($content['align'] ?? 'center') !== 'center')
                </div>
                <div class="order-2 mt-6 lg:mt-0 lg:flex lg:justify-end">
                    <a href="{{ $ctaUrl }}" class="btn-luxury btn-luxury-primary inline-flex items-center gap-2">
                        {{ $ctaText }}
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endif
        </div>
        @if($ctaText && $ctaUrl && ($content['align'] ?? 'center') === 'center')
            <div class="mt-6 flex justify-center">
                <a href="{{ $ctaUrl }}" class="btn-luxury btn-luxury-primary inline-flex items-center gap-2">
                    {{ $ctaText }}
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        @endif
    @endif
</div>
@endif
