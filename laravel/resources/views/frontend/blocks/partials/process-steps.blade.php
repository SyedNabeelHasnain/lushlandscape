{{-- Block: process_steps --}}
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = !empty($content['heading']) ? $content['heading'] : 'Our Process';
    $subtitle = $content['subtitle'] ?? '';
    $variant = $content['variant'] ?? 'numbered';
    $tone = $content['tone'] ?? 'light';
    $steps = $content['steps'] ?? [];
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'surface' => 'border-white/10 bg-white/6 text-white',
            'icon' => 'bg-white/10 text-white',
            'line' => 'bg-white/12',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'surface' => 'border-stone bg-cream text-ink',
            'icon' => 'bg-white text-forest',
            'line' => 'bg-stone',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'surface' => 'border-stone bg-white text-ink',
            'icon' => 'bg-forest text-white',
            'line' => 'bg-stone',
        ],
    };
@endphp

@if(!empty($steps))
<div class="space-y-10">
    <div class="max-w-3xl {{ $variant === 'timeline' ? '' : 'text-center mx-auto' }}">
        @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
        @if($heading)<h2 class="mt-4 text-h2 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h2>@endif
        @if($subtitle)<p class="mt-4 text-body-lg {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
    </div>

    @if($variant === 'feature_rows')
        <div class="space-y-8">
            @foreach($steps as $i => $step)
                <div class="flex gap-5 rounded-[1.75rem] border p-6 lg:p-8 {{ $toneMap['surface'] }}">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full {{ $toneMap['icon'] }}">
                        @if(!empty($step['icon']))
                            <i data-lucide="{{ $step['icon'] }}" class="w-6 h-6"></i>
                        @else
                            <span class="text-lg font-bold">{{ $i + 1 }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] {{ $toneMap['label'] }}">Step {{ $i + 1 }}</p>
                        <h3 class="mt-2 text-xl font-semibold {{ $toneMap['heading'] }}">{{ $step['title'] ?? '' }}</h3>
                        <p class="mt-3 text-sm leading-relaxed {{ $toneMap['sub'] }}">{{ $step['desc'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif($variant === 'timeline')
        <div class="space-y-6">
            @foreach($steps as $i => $step)
                <div class="relative pl-12">
                    @if(!$loop->last)
                        <div class="absolute left-[1.2rem] top-10 h-[calc(100%-1rem)] w-px {{ $toneMap['line'] }}"></div>
                    @endif
                    <div class="absolute left-0 top-0 flex h-10 w-10 items-center justify-center rounded-full {{ $toneMap['icon'] }}">
                        <span class="text-sm font-bold">{{ $i + 1 }}</span>
                    </div>
                    <div class="rounded-[1.5rem] border p-6 {{ $toneMap['surface'] }}">
                        <h3 class="text-xl font-semibold {{ $toneMap['heading'] }}">{{ $step['title'] ?? '' }}</h3>
                        <p class="mt-3 text-sm leading-relaxed {{ $toneMap['sub'] }}">{{ $step['desc'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            @foreach($steps as $i => $step)
                <div class="relative">
                    @if($i < count($steps) - 1)
                        <div class="absolute left-full top-8 hidden h-px w-full -translate-x-1/2 lg:block {{ $toneMap['line'] }}"></div>
                    @endif
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full text-xl font-bold {{ $toneMap['icon'] }}">
                            {{ $i + 1 }}
                        </div>
                        @if(!empty($step['icon']))
                            <i data-lucide="{{ $step['icon'] }}" class="mb-2 h-6 w-6 {{ $tone === 'dark' ? 'text-white/72' : 'text-forest' }}"></i>
                        @endif
                        <h3 class="mb-2 font-semibold {{ $toneMap['heading'] }}">{{ $step['title'] ?? '' }}</h3>
                        <p class="text-sm {{ $toneMap['sub'] }}">{{ $step['desc'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endif
