@php
    $seasons     = $content['seasons'] ?? [];
    $seasonIcons = ['spring' => 'flower-2', 'summer' => 'sun', 'fall' => 'leaf', 'autumn' => 'leaf', 'winter' => 'snowflake'];
    $seasonColors = [
        'spring' => 'bg-forest/5 border-forest/20 text-forest',
        'summer' => 'bg-cream border-stone text-ink',
        'fall'   => 'bg-cream-warm border-stone text-ink',
        'autumn' => 'bg-cream-warm border-stone text-ink',
        'winter' => 'bg-white border-stone text-ink',
    ];
@endphp
@if(!empty($seasons))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['heading']))
    <div class="text-center mb-14 reveal">
        <h2 class="text-h2 font-heading font-bold text-forest">{{ $content['heading'] }}</h2>
    </div>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min(count($seasons), 4) }} gap-6 reveal-stagger">
        @foreach($seasons as $season)
        @php
            $key   = strtolower($season['season'] ?? '');
            $color = $seasonColors[$key] ?? 'bg-cream border-stone text-ink';
            $icon  = $season['icon'] ?? $seasonIcons[$key] ?? 'calendar';
        @endphp
        <div class="border p-8 {{ $color }} hover:border-forest/20 hover:shadow-luxury transition-all duration-500">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-forest/6 flex items-center justify-center">
                    <i data-lucide="{{ $icon }}" class="w-5 h-5 text-forest"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-forest">{{ $season['season'] ?? '' }}</span>
            </div>
            @if(!empty($season['title']))
            <h3 class="font-heading font-bold text-lg text-ink mb-3">{{ $season['title'] }}</h3>
            @endif
            @if(!empty($season['description']))
            <p class="text-sm text-text-secondary leading-relaxed">{{ $season['description'] }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
