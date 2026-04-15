@php
    $slides     = $content['slides'] ?? [];
    $autoplay   = !empty($content['autoplay']);
    $showDots   = $content['show_dots'] ?? true;
    $showArrows = $content['show_arrows'] ?? true;
    $uid        = 'carousel-' . uniqid();
@endphp
@if(!empty($slides))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-8">
    <div class="relative overflow-hidden group" id="{{ $uid }}"
         x-data="{
             active: 0, total: {{ count($slides) }},
             interval: null,
             @if($autoplay) autoplay: true, @else autoplay: false, @endif
             init() { if (this.autoplay) this.interval = setInterval(() => this.next(), 4500); },
             next() { this.active = (this.active + 1) % this.total; },
             prev() { this.active = (this.active - 1 + this.total) % this.total; },
         }">
        <div class="flex transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]"
             :style="'transform: translateX(-' + (active * 100) + '%)'">
            @foreach($slides as $slide)
            @php $asset = !empty($slide['media_id']) ? ($mediaLookup[$slide['media_id']] ?? null) : null; @endphp
            <div class="w-full shrink-0 relative">
                @if($asset)
                <img src="{{ $asset->url }}" alt="{{ $asset->default_alt_text ?? ($slide['caption'] ?? '') }}"
                     class="w-full aspect-video object-cover" loading="lazy">
                @endif
                @if(!empty($slide['caption']))
                <p class="absolute bottom-0 left-0 right-0 bg-black/50 backdrop-blur-sm text-white text-sm text-center py-3 px-4">{{ $slide['caption'] }}</p>
                @endif
            </div>
            @endforeach
        </div>

        @if($showArrows && count($slides) > 1)
        <button type="button" x-on:click="prev()"
                class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-white/20 transition-all duration-300 opacity-0 group-hover:opacity-100"
                aria-label="Previous slide">
            <i data-lucide="chevron-left" class="w-5 h-5 text-white"></i>
        </button>
        <button type="button" x-on:click="next()"
                class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center hover:bg-white/20 transition-all duration-300 opacity-0 group-hover:opacity-100"
                aria-label="Next slide">
            <i data-lucide="chevron-right" class="w-5 h-5 text-white"></i>
        </button>
        @endif

        @if($showDots && count($slides) > 1)
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
            @foreach($slides as $i => $slide)
            <button type="button" x-on:click="active = {{ $i }}"
                    class="h-1 transition-all duration-300"
                    :class="active === {{ $i }} ? 'bg-white w-8' : 'bg-white/40 w-4'"></button>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif
