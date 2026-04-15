{{-- Block: trust_badges --}}
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = $content['heading'] ?? '';
    $subtitle = $content['subtitle'] ?? '';
    $variant = $content['variant'] ?? 'grid';
    $tone = $content['tone'] ?? 'light';
    $badges = $content['badges'] ?? [
        ['icon' => 'shield-check', 'title' => 'Licensed & Insured', 'desc' => 'Fully licensed and insured for your peace of mind.'],
        ['icon' => 'award', 'title' => '10-Year Warranty', 'desc' => 'Industry-leading workmanship guarantee.'],
        ['icon' => 'clock', 'title' => 'On-Time Delivery', 'desc' => 'We complete projects on schedule, every time.'],
        ['icon' => 'leaf', 'title' => 'Premium Materials', 'desc' => 'Only the highest quality materials sourced responsibly.'],
    ];
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'card' => 'border-white/10 bg-white/6 text-white',
            'icon' => 'bg-white/10 text-white',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-cream text-ink',
            'icon' => 'bg-white text-forest',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-white text-ink',
            'icon' => 'bg-forest/10 text-forest',
        ],
    };
@endphp

<div class="space-y-8">
    @if($eyebrow || $heading || $subtitle)
        <div class="max-w-3xl {{ $variant === 'compact' ? '' : 'mx-auto text-center' }}">
            @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
            @if($heading)<h2 class="mt-4 text-h3 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h2>@endif
            @if($subtitle)<p class="mt-4 text-body {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
        </div>
    @endif

    @if($variant === 'compact')
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach($badges as $badge)
                <div class="flex items-start gap-3 rounded-[1.25rem] border p-4 {{ $toneMap['card'] }}">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg shrink-0 {{ $toneMap['icon'] }}">
                        <i data-lucide="{{ $badge['icon'] ?? 'check' }}" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold {{ $toneMap['heading'] }}">{{ $badge['title'] ?? '' }}</h4>
                        <p class="mt-0.5 text-xs {{ $toneMap['sub'] }}">{{ $badge['desc'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach($badges as $badge)
                <div class="{{ $variant === 'cards' ? 'editorial-card rounded-[1.5rem]' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg shrink-0 {{ $toneMap['icon'] }}">
                            <i data-lucide="{{ $badge['icon'] ?? 'check' }}" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold {{ $toneMap['heading'] }}">{{ $badge['title'] ?? '' }}</h4>
                            <p class="mt-0.5 text-xs {{ $toneMap['sub'] }}">{{ $badge['desc'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
