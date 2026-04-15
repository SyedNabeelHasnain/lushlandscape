@php
    $badges = $content['badges'] ?? [];
    $style  = $content['style'] ?? 'icon';
@endphp
@if(!empty($badges))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
    @if(!empty($content['heading']))
    <div class="text-center mb-10 reveal">
        <h2 class="text-xl font-heading font-bold text-forest">{{ $content['heading'] }}</h2>
    </div>
    @endif

    @if($style === 'pill')
    <div class="flex flex-wrap gap-3 justify-center reveal-stagger">
        @foreach($badges as $badge)
        @if(!empty($badge['text']))
        <span class="inline-flex items-center gap-2 bg-forest/6 text-forest font-medium text-sm px-5 py-2.5">
            @if(!empty($badge['icon']))
            <i data-lucide="{{ $badge['icon'] }}" class="w-4 h-4"></i>
            @endif
            {{ $badge['text'] }}
        </span>
        @endif
        @endforeach
    </div>

    @elseif($style === 'image')
    <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12 reveal-stagger">
        @foreach($badges as $badge)
        @php $asset = !empty($badge['media_id']) ? ($mediaLookup[$badge['media_id']] ?? null) : null; @endphp
        <div class="flex flex-col items-center gap-3">
            @if($asset)
            <img src="{{ $asset->url }}" alt="{{ $badge['text'] ?? '' }}" class="h-12 md:h-16 w-auto object-contain" loading="lazy">
            @endif
            @if(!empty($badge['text']))
            <span class="text-xs font-medium text-text-secondary text-center">{{ $badge['text'] }}</span>
            @endif
        </div>
        @endforeach
    </div>

    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-{{ min(count($badges), 5) }} gap-5 md:gap-6 reveal-stagger">
        @foreach($badges as $badge)
        <div class="flex items-center gap-4 bg-white border border-stone p-5 hover:border-forest/20 hover:shadow-luxury transition-all duration-500">
            @if(!empty($badge['icon']))
            <div class="w-12 h-12 bg-forest/6 flex items-center justify-center shrink-0">
                <i data-lucide="{{ $badge['icon'] }}" class="w-5 h-5 text-forest"></i>
            </div>
            @endif
            @if(!empty($badge['text']))
            <span class="text-sm font-medium text-ink leading-tight">{{ $badge['text'] }}</span>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
@endif
