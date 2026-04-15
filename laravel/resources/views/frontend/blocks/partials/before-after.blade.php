{{-- Block: before_after --}}
@php
    $beforeId = $content['before_media_id'] ?? null;
    $afterId = $content['after_media_id'] ?? null;
    $caption = $content['caption'] ?? '';
    $before = $beforeId ? \App\Models\MediaAsset::find($beforeId) : null;
    $after = $afterId ? \App\Models\MediaAsset::find($afterId) : null;
@endphp
@if($before && $after)
<div x-data="{ position: 50 }" class="relative overflow-hidden rounded-xl aspect-video select-none"
     @mousemove="position = ($event.offsetX / $el.offsetWidth) * 100"
     @touchmove="position = ($event.touches[0].clientX - $el.getBoundingClientRect().left) / $el.offsetWidth * 100">
    <img src="{{ $after->url }}" alt="After" loading="lazy" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 overflow-hidden" :style="'width: ' + position + '%'">
        <img src="{{ $before->url }}" alt="Before" loading="lazy" class="absolute inset-0 w-full h-full object-cover" :style="'width: ' + (100 / position * 100) + '%'">
    </div>
    <div class="absolute top-1/2 -translate-y-1/2 w-1 h-full bg-white shadow-lg" :style="'left: ' + position + '%'">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center">
            <i data-lucide="arrow-left-right" class="w-4 h-4 text-forest"></i>
        </div>
    </div>
    <div class="absolute top-4 left-4 bg-black/60 text-white text-xs px-3 py-1 rounded-full">Before</div>
    <div class="absolute top-4 right-4 bg-black/60 text-white text-xs px-3 py-1 rounded-full">After</div>
</div>
@if($caption)<p class="mt-3 text-sm text-text-secondary text-center">{{ $caption }}</p>@endif
@endif
