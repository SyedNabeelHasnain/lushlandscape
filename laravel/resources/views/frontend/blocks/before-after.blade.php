@php
    $beforeAsset = !empty($content['before_media_id']) ? ($mediaLookup[$content['before_media_id']] ?? null) : null;
    $afterAsset  = !empty($content['after_media_id'])  ? ($mediaLookup[$content['after_media_id']]  ?? null) : null;
    $beforeLabel = $content['before_label'] ?? 'Before';
    $afterLabel  = $content['after_label'] ?? 'After';
@endphp
@if($beforeAsset && $afterAsset)
<div class="max-w-4xl mx-auto px-6 lg:px-12 py-12 reveal">
    <div class="relative overflow-hidden select-none border border-stone shadow-luxury" x-data="{ pos: 50 }"
         x-on:mousemove="pos = Math.max(5, Math.min(95, ($event.offsetX / $el.offsetWidth) * 100))"
         x-on:touchmove.prevent="pos = Math.max(5, Math.min(95, (($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.offsetWidth) * 100))">
        <img src="{{ $afterAsset->url }}" alt="{{ $afterLabel }}" class="w-full aspect-video object-cover" loading="lazy">
        <div class="absolute inset-0 overflow-hidden" :style="'width: ' + pos + '%'">
            <img src="{{ $beforeAsset->url }}" alt="{{ $beforeLabel }}" class="w-full h-full object-cover" style="min-width: 100vw; max-width: none; width: 100%;"
                 :style="'width: ' + (100 / pos * 100) + '%; max-width: none;'" loading="lazy">
        </div>
        <div class="absolute top-0 bottom-0 w-0.5 bg-white shadow-lg z-10" :style="'left: ' + pos + '%'">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white shadow-luxury flex items-center justify-center">
                <i data-lucide="move-horizontal" class="w-5 h-5 text-forest"></i>
            </div>
        </div>
        <span class="absolute top-4 left-4 bg-forest/80 text-white text-[10px] font-semibold px-3 py-1.5 tracking-[0.15em] uppercase">{{ $beforeLabel }}</span>
        <span class="absolute top-4 right-4 bg-forest/80 text-white text-[10px] font-semibold px-3 py-1.5 tracking-[0.15em] uppercase">{{ $afterLabel }}</span>
    </div>
    @if(!empty($content['caption']))
    <p class="mt-4 text-sm text-text-secondary text-center leading-relaxed">{{ $content['caption'] }}</p>
    @endif
</div>
@endif
