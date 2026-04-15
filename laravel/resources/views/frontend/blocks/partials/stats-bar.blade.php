{{-- Block: stats_bar --}}
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = $content['heading'] ?? '';
    $subtitle = $content['subtitle'] ?? '';
    $variant = $content['variant'] ?? 'metrics';
    $tone = $content['tone'] ?? 'light';
    $stats = $content['stats'] ?? [
        ['number' => '10+', 'label' => 'Years Experience', 'icon' => 'award'],
        ['number' => '500+', 'label' => 'Projects Completed', 'icon' => 'check-circle'],
        ['number' => '10', 'label' => 'Year Warranty', 'icon' => 'shield-check'],
        ['number' => '100%', 'label' => 'Satisfaction Rate', 'icon' => 'heart'],
    ];
    $bandColsClass = match (min(max(count($stats), 1), 4)) {
        1 => 'md:grid-cols-1',
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        default => 'md:grid-cols-4',
    };
    $heroColsClass = match (min(max(count($stats), 1), 4)) {
        1 => 'lg:grid-cols-1',
        2 => 'lg:grid-cols-2',
        3 => 'lg:grid-cols-3',
        default => 'lg:grid-cols-4',
    };
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'surface' => 'border-white/10 bg-white/6 text-white',
            'number' => 'text-white',
            'icon' => 'bg-white/10 text-white',
        ],
        'forest' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'surface' => 'border-white/10 bg-luxury-green-deep text-white',
            'number' => 'text-white',
            'icon' => 'border border-white/15 text-white',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'surface' => 'border-stone bg-white text-ink',
            'number' => 'text-ink',
            'icon' => 'bg-forest/10 text-forest',
        ],
    };
@endphp

<div class="space-y-8">
    @if($eyebrow || $heading || $subtitle)
        <div class="{{ $variant === 'hero_panel' ? 'text-center' : 'max-w-3xl' }}">
            @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
            @if($heading)<h2 class="mt-4 text-h3 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h2>@endif
            @if($subtitle)<p class="mt-4 text-body {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
        </div>
    @endif

    @if($variant === 'trust_band')
        <div class="grid gap-px overflow-hidden rounded-[1.75rem] border {{ $tone === 'light' ? 'border-stone bg-stone' : 'border-white/10 bg-white/10' }} {{ $bandColsClass }}">
            @foreach($stats as $stat)
                <div class="px-6 py-8 text-left {{ $tone === 'light' ? 'bg-white' : 'bg-black/10' }}">
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] {{ $toneMap['label'] }}">{{ $stat['number'] ?? '' }}</p>
                    <p class="mt-2 text-h3 font-heading font-bold leading-none {{ $toneMap['heading'] }}">{{ $stat['label'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    @elseif($variant === 'hero_panel')
        <div class="grid gap-px overflow-hidden rounded-[1.75rem] border {{ $tone === 'light' ? 'border-stone bg-stone' : 'border-white/10 bg-white/10' }} {{ $heroColsClass }}">
            @foreach($stats as $stat)
                <div class="px-8 py-6 text-left {{ $tone === 'light' ? 'bg-white/96' : 'bg-white/4' }}">
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] {{ $toneMap['label'] }}">{{ $stat['label'] ?? '' }}</p>
                    <p class="mt-2 text-[2rem] leading-none {{ $toneMap['heading'] }}">{{ $stat['number'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
            @foreach($stats as $stat)
                <div class="text-center">
                    <div class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-full {{ $toneMap['icon'] }}">
                        <i data-lucide="{{ $stat['icon'] ?? 'star' }}" class="w-5 h-5"></i>
                    </div>
                    <div class="text-3xl font-bold {{ $toneMap['number'] }}">{{ $stat['number'] }}</div>
                    <div class="mt-1 text-sm {{ $toneMap['sub'] }}">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
