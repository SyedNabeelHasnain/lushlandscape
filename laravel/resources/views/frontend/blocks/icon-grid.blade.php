@php
    $items   = $content['items'] ?? [];
    $cols    = $content['columns'] ?? '3';
    $style   = $content['style'] ?? 'card';
    $gridCls = match($cols) {
        '2' => 'grid-cols-1 sm:grid-cols-2',
        '4' => 'grid-cols-2 md:grid-cols-4',
        '6' => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-6',
        default => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3',
    };
@endphp
@if(!empty($items))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-8">
    @if(!empty($content['heading']))
    <h2 class="text-2xl font-heading font-bold tracking-tight text-text mb-8 text-center">{{ $content['heading'] }}</h2>
    @endif
    <div class="grid {{ $gridCls }} gap-6">
        @foreach($items as $item)
        @if($style === 'card')
        <div class="bg-white border border-stone p-6 hover:shadow-luxury transition-shadow text-center">
            @if(!empty($item['icon']))
            <div class="w-14 h-14 border border-stone bg-white flex items-center justify-center mx-auto mb-4">
                <i data-lucide="{{ $item['icon'] }}" class="w-7 h-7 text-forest"></i>
            </div>
            @endif
            @if(!empty($item['title']))
            <h3 class="font-bold text-text mb-2">{{ $item['title'] }}</h3>
            @endif
            @if(!empty($item['description']))
            <p class="text-sm text-text-secondary leading-relaxed">{{ $item['description'] }}</p>
            @endif
        </div>
        @elseif($style === 'circle')
        <div class="text-center">
            @if(!empty($item['icon']))
            <div class="w-16 h-16 border border-stone bg-white flex items-center justify-center mx-auto mb-3">
                <i data-lucide="{{ $item['icon'] }}" class="w-7 h-7 text-forest"></i>
            </div>
            @endif
            @if(!empty($item['title']))
            <h3 class="font-bold text-text mb-1">{{ $item['title'] }}</h3>
            @endif
            @if(!empty($item['description']))
            <p class="text-sm text-text-secondary">{{ $item['description'] }}</p>
            @endif
        </div>
        @else
        <div class="flex gap-4">
            @if(!empty($item['icon']))
            <div class="w-10 h-10 border border-stone bg-white flex items-center justify-center shrink-0">
                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 text-forest"></i>
            </div>
            @endif
            <div>
                @if(!empty($item['title']))
                <h3 class="font-semibold text-text mb-1">{{ $item['title'] }}</h3>
                @endif
                @if(!empty($item['description']))
                <p class="text-sm text-text-secondary leading-relaxed">{{ $item['description'] }}</p>
                @endif
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endif
