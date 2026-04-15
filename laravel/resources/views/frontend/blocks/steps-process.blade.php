@php
    $steps  = $content['steps'] ?? [];
    $layout = $content['layout'] ?? 'horizontal';
@endphp
@if(!empty($steps))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['heading']))
    <div class="text-center mb-16 reveal">
        <h2 class="text-h2 font-heading font-bold tracking-tight text-forest">{{ $content['heading'] }}</h2>
    </div>
    @endif

    @if($layout === 'horizontal')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min(count($steps), 4) }} gap-8 md:gap-10 reveal-stagger">
        @foreach($steps as $i => $step)
        <div class="relative text-center">
            <div class="w-16 h-16 {{ !empty($step['icon']) ? 'bg-forest/6' : 'bg-forest' }} flex items-center justify-center mx-auto mb-6">
                @if(!empty($step['icon']))
                <i data-lucide="{{ $step['icon'] }}" class="w-6 h-6 text-forest"></i>
                @else
                <span class="text-xl font-bold text-white">{{ $i + 1 }}</span>
                @endif
            </div>
            @if(!empty($step['title']))
            <h3 class="font-heading font-bold text-ink mb-3">{{ $step['title'] }}</h3>
            @endif
            @if(!empty($step['description']))
            <p class="text-sm text-text-secondary leading-relaxed">{{ $step['description'] }}</p>
            @endif
            @if(!$loop->last)
            <div class="hidden lg:block absolute top-8 left-[calc(50%+2.5rem)] right-[calc(-50%+2.5rem)] h-px bg-forest/15"></div>
            @endif
        </div>
        @endforeach
    </div>

    @elseif($layout === 'alternating')
    <div class="relative max-w-3xl mx-auto">
        <div class="absolute left-1/2 top-0 bottom-0 w-px bg-forest/15 -translate-x-1/2 hidden md:block"></div>
        <div class="space-y-10 reveal-stagger">
            @foreach($steps as $i => $step)
            <div class="relative flex items-start gap-6 {{ $i % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' }}">
                <div class="absolute left-1/2 -translate-x-1/2 w-12 h-12 bg-forest flex items-center justify-center z-10 hidden md:flex shadow-lg shadow-forest/20">
                    <span class="text-sm font-bold text-white">{{ $i + 1 }}</span>
                </div>
                <div class="md:w-[calc(50%-2rem)] {{ $i % 2 === 0 ? 'md:text-right md:pr-10' : 'md:pl-10' }}">
                    <div class="flex items-center gap-3 md:hidden mb-3">
                        <div class="w-10 h-10 bg-forest flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-white">{{ $i + 1 }}</span>
                        </div>
                        <h3 class="font-heading font-bold text-ink">{{ $step['title'] ?? '' }}</h3>
                    </div>
                    <h3 class="font-heading font-bold text-ink mb-2 hidden md:block">{{ $step['title'] ?? '' }}</h3>
                    @if(!empty($step['description']))
                    <p class="text-sm text-text-secondary leading-relaxed">{{ $step['description'] }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @else
    <div class="max-w-2xl mx-auto space-y-8 reveal-stagger">
        @foreach($steps as $i => $step)
        <div class="flex gap-6">
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-forest flex items-center justify-center shrink-0 shadow-lg shadow-forest/20">
                    @if(!empty($step['icon']))
                    <i data-lucide="{{ $step['icon'] }}" class="w-5 h-5 text-white"></i>
                    @else
                    <span class="text-sm font-bold text-white">{{ $i + 1 }}</span>
                    @endif
                </div>
                @if(!$loop->last)
                <div class="w-px flex-1 bg-forest/15 mt-3"></div>
                @endif
            </div>
            <div class="pb-8">
                @if(!empty($step['title']))
                <h3 class="font-heading font-bold text-ink mb-2">{{ $step['title'] }}</h3>
                @endif
                @if(!empty($step['description']))
                <p class="text-sm text-text-secondary leading-relaxed">{{ $step['description'] }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif
