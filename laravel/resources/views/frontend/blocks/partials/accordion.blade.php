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
<div class="space-y-2" x-data="{ activeIndex: null }">
    @foreach($items as $i => $item)
    @php
        $itemId = 'accordion-item-' . uniqid();
    @endphp
    <div class="border rounded-lg {{ $classes }} overflow-hidden">
        <button 
            type="button"
            class="w-full flex items-center justify-between text-left p-5 font-semibold text-text focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-forest transition-colors"
            :aria-expanded="activeIndex === {{ $i }}"
            aria-controls="{{ $itemId }}"
            @click="activeIndex === {{ $i }} ? activeIndex = null : activeIndex = {{ $i }}"
        >
            <span>{{ $item['title'] ?? '' }}</span>
            <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform" :class="activeIndex === {{ $i }} ? 'rotate-180' : ''"></i>
        </button>
        <div 
            id="{{ $itemId }}"
            x-show="activeIndex === {{ $i }}"
            x-collapse
            x-cloak
            role="region"
            class="px-5 pb-5 text-text-secondary leading-relaxed"
        >
            {!! $item['content'] ?? '' !!}
        </div>
    </div>
    @endforeach
</div>
@endif
