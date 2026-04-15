@php
    $type    = $content['type'] ?? 'info';
    $styles  = match($type) {
        'success' => 'bg-forest/5 border-forest/20 text-forest',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'promo'   => 'bg-forest text-white border-forest',
        default   => 'bg-cream border-stone text-ink',
    };
    $dismissible = !empty($content['dismissible']);
@endphp
@if(!empty($content['text']))
<div class="border-b {{ $styles }}"
     @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif>
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3 flex items-center justify-center gap-3 text-sm">
        @if(!empty($content['icon']))
        <i data-lucide="{{ $content['icon'] }}" class="w-4 h-4 shrink-0"></i>
        @endif
        <span class="font-medium">{{ $content['text'] }}</span>
        @if(!empty($content['link_url']))
        <a href="{{ $content['link_url'] }}" class="font-bold underline underline-offset-4 hover:no-underline whitespace-nowrap transition-all">
            {{ $content['link_text'] ?? 'Learn more' }} →
        </a>
        @endif
        @if($dismissible)
        <button type="button" x-on:click="show = false" class="ml-auto opacity-60 hover:opacity-100 shrink-0 transition-opacity" aria-label="Dismiss notice">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
        @endif
    </div>
</div>
@endif
