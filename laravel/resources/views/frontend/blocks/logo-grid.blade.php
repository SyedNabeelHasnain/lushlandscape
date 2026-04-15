@php
    $logos     = $content['logos'] ?? [];
    $cols      = $content['columns'] ?? '4';
    $grayscale = !empty($content['grayscale']);
    $gridCls   = match($cols) {
        '3' => 'grid-cols-2 sm:grid-cols-3',
        '5' => 'grid-cols-2 sm:grid-cols-3 md:grid-cols-5',
        '6' => 'grid-cols-3 md:grid-cols-6',
        default => 'grid-cols-2 sm:grid-cols-4',
    };
@endphp
@if(!empty($logos))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
    @if(!empty($content['heading']))
    <div class="text-center mb-10 reveal">
        <h2 class="text-xl font-heading font-bold text-forest">{{ $content['heading'] }}</h2>
    </div>
    @endif
    <div class="grid {{ $gridCls }} gap-6 items-center reveal-stagger">
        @foreach($logos as $logo)
        @php $asset = !empty($logo['media_id']) ? ($mediaLookup[$logo['media_id']] ?? null) : null; @endphp
        @if($asset)
        @php $Tag = !empty($logo['url']) ? 'a' : 'div'; @endphp
        <{{ $Tag }} {!! !empty($logo['url']) ? 'href="' . e($logo['url']) . '" target="_blank" rel="noopener noreferrer"' : '' !!}
            class="flex items-center justify-center p-5 border border-transparent hover:border-forest/10 {{ $grayscale ? 'grayscale hover:grayscale-0 opacity-60 hover:opacity-100' : '' }} transition-all duration-500"
            @if(!empty($logo['name'])) title="{{ $logo['name'] }}" @endif>
            <img src="{{ $asset->url }}" alt="{{ $logo['name'] ?? $asset->default_alt_text ?? '' }}"
                 class="max-h-12 md:max-h-16 w-auto object-contain" loading="lazy">
        </{{ $Tag }}>
        @endif
        @endforeach
    </div>
</div>
@endif
