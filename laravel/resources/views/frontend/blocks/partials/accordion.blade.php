{{-- Block: accordion --}}
@php
    $style = $content['style'] ?? 'default';
    $items = $content['items'] ?? [];
    $styleClasses = [
        'default' => 'bg-white border-stone',
        'bordered' => 'bg-white border-2 border-stone',
        'minimal' => 'bg-transparent border-b border-stone',
    ];
    $classes = $styleClasses[$style] ?? $styleClasses['default'];
@endphp
@if(!empty($items))
<div class="space-y-2">
    @foreach($items as $i => $item)
    <details class="group border rounded-lg {{ $classes }}" {{ ($item['open'] ?? false) ? 'open' : '' }}>
        <summary class="flex items-center justify-between cursor-pointer p-5 font-semibold text-text list-none">
            {{ $item['title'] ?? '' }}
            <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform group-open:rotate-180"></i>
        </summary>
        <div class="px-5 pb-5 text-text-secondary leading-relaxed">
            {!! $item['content'] ?? '' !!}
        </div>
    </details>
    @endforeach
</div>
@endif
