@php
    $areas   = $content['areas'] ?? [];
    $layout = $content['layout'] ?? 'grid';
    $cols    = $content['columns'] ?? '3';
    $gridCls = match($cols) { '2' => 'grid-cols-1 sm:grid-cols-2', '4' => 'grid-cols-2 md:grid-cols-4', default => 'grid-cols-2 md:grid-cols-3' };
@endphp
@if(!empty($areas))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['heading']))
    <h2 class="text-h3 font-heading font-bold text-forest mb-4">{{ $content['heading'] }}</h2>
    @endif
    @if(!empty($content['description']))
    <p class="text-text-secondary mb-10 max-w-2xl leading-relaxed">{{ $content['description'] }}</p>
    @endif
    @if($layout === 'inline')
        <div class="flex flex-wrap items-center gap-x-3 gap-y-3 reveal">
            @foreach($areas as $area)
                @if(!empty($area['name']))
                    @php
                        $url = $area['url'] ?? '';
                    @endphp
                    @if($loop->first === false)
                        <span class="text-stone">·</span>
                    @endif
                    @if($url)
                        <a href="{{ $url }}" class="text-sm font-semibold text-ink hover:text-forest transition-colors">{{ $area['name'] }}</a>
                    @else
                        <span class="text-sm font-semibold text-ink">{{ $area['name'] }}</span>
                    @endif
                @endif
            @endforeach
        </div>
    @else
        <div class="grid {{ $gridCls }} gap-3 reveal-stagger">
            @foreach($areas as $area)
            @if(!empty($area['name']))
            @if(!empty($area['url']))
            <a href="{{ $area['url'] }}" class="flex items-center gap-3 bg-white border border-stone px-5 py-4 hover:border-forest/20 hover:shadow-luxury transition-all duration-500 group">
                <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
                <span class="text-sm font-medium text-ink group-hover:text-forest transition-colors duration-300">{{ $area['name'] }}</span>
            </a>
            @else
            <div class="flex items-center gap-3 bg-white border border-stone px-5 py-4">
                <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
                <span class="text-sm font-medium text-ink">{{ $area['name'] }}</span>
            </div>
            @endif
            @endif
            @endforeach
        </div>
    @endif
</div>
@endif
