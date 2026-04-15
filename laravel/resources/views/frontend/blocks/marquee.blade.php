@php
    $items     = $content['items'] ?? [];
    $duration  = match($content['speed'] ?? 'normal') { 'slow' => '40s', 'fast' => '15s', default => '25s' };
    $direction = ($content['direction'] ?? 'left') === 'right' ? 'reverse' : 'normal';
@endphp
@if(!empty($items))
<div class="overflow-hidden py-6 bg-cream border-y border-stone">
    <div class="flex gap-8 animate-marquee whitespace-nowrap"
         style="animation-duration: {{ $duration }}; animation-direction: {{ $direction }}">
        {{-- Double the items for seamless loop --}}
        @foreach(array_merge($items, $items) as $item)
        @php $asset = !empty($item['media_id']) ? ($mediaLookup[$item['media_id']] ?? null) : null; @endphp
        <div class="flex items-center gap-3 shrink-0">
            @if($asset)
            <img src="{{ $asset->url }}" alt="{{ $asset->default_alt_text ?? '' }}" class="h-8 w-auto object-contain opacity-60" loading="lazy">
            @endif
            @if(!empty($item['text']))
            <span class="text-text-secondary font-medium text-sm">{{ $item['text'] }}</span>
            @endif
            <span class="text-forest/30 ml-4">✦</span>
        </div>
        @endforeach
    </div>
</div>
<style>
@keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }
.animate-marquee { animation: marquee linear infinite; }
</style>
@endif
