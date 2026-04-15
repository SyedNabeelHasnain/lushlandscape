{{-- Block: cta_section --}}
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $title = !empty($content['title']) ? $content['title'] : 'Ready to Transform Your Outdoor Space?';
    $subtitle = !empty($content['subtitle']) ? $content['subtitle'] : 'Book your on-site consultation and receive a clear scope plan with thoughtful material direction.';
    $variant = $content['variant'] ?? 'panel';
    $tone = $content['tone'] ?? 'cream';
    $btnText = !empty($content['button_text']) ? $content['button_text'] : 'Book a Consultation';
    $btnUrl = !empty($content['button_url']) ? $content['button_url'] : '/contact';
    $btnSecondaryText = $content['button_secondary_text'] ?? '';
    $btnSecondaryUrl = $content['button_secondary_url'] ?? '';
    $toneMap = match ($tone) {
        'dark' => [
            'shell' => 'bg-luxury-dark border border-white/10 text-white',
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'primary' => 'btn-luxury btn-luxury-white',
            'secondary' => 'btn-luxury border border-white/15 text-white hover:bg-white hover:text-forest',
        ],
        'forest' => [
            'shell' => 'bg-luxury-green-deep border border-white/10 text-white',
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'primary' => 'btn-luxury btn-luxury-white',
            'secondary' => 'btn-luxury border border-white/15 text-white hover:bg-white hover:text-forest',
        ],
        'light' => [
            'shell' => 'bg-white border border-stone shadow-luxury text-ink',
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'primary' => 'btn-luxury btn-luxury-primary',
            'secondary' => 'btn-luxury border border-stone text-ink hover:border-forest/20',
        ],
        default => [
            'shell' => 'bg-cream border border-stone shadow-editorial text-ink',
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'primary' => 'btn-luxury btn-luxury-primary',
            'secondary' => 'btn-luxury border border-stone text-ink hover:border-forest/20',
        ],
    };
@endphp

<div class="{{ $variant === 'inline' ? '' : 'rounded-[2rem] p-8 lg:p-12 '.$toneMap['shell'] }}">
    <div class="{{ $variant === 'split' ? 'grid gap-10 lg:grid-cols-[1.1fr_auto] lg:items-end' : 'text-center' }}">
        <div class="{{ $variant === 'split' ? 'max-w-3xl' : 'mx-auto max-w-3xl' }}">
            @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
            @if($title)<h2 class="mt-4 text-h2 font-heading font-bold {{ $toneMap['heading'] }}">{{ $title }}</h2>@endif
            @if($subtitle)<p class="mt-4 text-body-lg {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
        </div>
        <div class="mt-8 flex flex-wrap items-center {{ $variant === 'split' ? 'justify-start lg:justify-end' : 'justify-center' }} gap-4">
            <a href="{{ $btnUrl }}" class="inline-flex items-center gap-2 {{ $toneMap['primary'] }}">
                {{ $btnText }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
            @if($btnSecondaryText)
            <a href="{{ $btnSecondaryUrl }}" class="inline-flex items-center gap-2 {{ $toneMap['secondary'] }}">
                {{ $btnSecondaryText }}
            </a>
            @endif
        </div>
    </div>
</div>
