@php
    $project = $context['project'] ?? null;
    $beforeUrl = $project && $project->beforeMedia ? $project->beforeMedia->url : null;
    $afterUrl = $project && $project->afterMedia ? $project->afterMedia->url : null;
@endphp
@if($beforeUrl && $afterUrl)
<section class="bg-airy-gradient py-20 lg:py-32 px-5 lg:px-12 overflow-hidden section-fade-to-white">
    <div class="max-w-6xl mx-auto gs-reveal">
        <div class="text-center mb-10 lg:mb-16">
            <h2 class="fluid-heading text-forest mb-4 lg:mb-6 word-wrap-safe">{{ $content['heading'] ?? 'Site Transformation' }}</h2>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light max-w-2xl mx-auto">{{ $content['description'] }}</p>
            @endif
        </div>

        <div class="relative w-full aspect-[4/3] lg:aspect-[16/9] overflow-hidden rounded-sm border border-black/10 shadow-xl" 
             x-data="{ position: 50, dragging: false }"
             @mousemove="if(dragging) { const rect = $el.getBoundingClientRect(); position = Math.max(0, Math.min(100, (($event.clientX - rect.left) / rect.width) * 100)) }"
             @touchmove="if(dragging) { const rect = $el.getBoundingClientRect(); position = Math.max(0, Math.min(100, (($event.touches[0].clientX - rect.left) / rect.width) * 100)) }"
             @mouseup="dragging = false"
             @mouseleave="dragging = false"
             @touchend="dragging = false">
             
            {{-- After Image (Background) --}}
            <img src="{{ $afterUrl }}" alt="After construction" class="absolute inset-0 w-full h-full object-cover select-none" draggable="false">
            <div class="absolute top-4 right-4 bg-forest/80 backdrop-blur-md px-3 py-1 rounded-sm text-white text-[9px] font-bold uppercase tracking-[0.2em]">After</div>

            {{-- Before Image (Clipped) --}}
            <div class="absolute inset-0 w-full h-full select-none overflow-hidden" :style="`clip-path: inset(0 ${100 - position}% 0 0);`">
                <img src="{{ $beforeUrl }}" alt="Before construction" class="absolute inset-0 w-full h-full object-cover select-none" draggable="false">
                <div class="absolute top-4 left-4 bg-ink/80 backdrop-blur-md px-3 py-1 rounded-sm text-white text-[9px] font-bold uppercase tracking-[0.2em]">Before</div>
            </div>

            {{-- Slider Handle --}}
            <div class="absolute top-0 bottom-0 w-1 bg-white cursor-ew-resize flex items-center justify-center shadow-[0_0_10px_rgba(0,0,0,0.3)]"
                 :style="`left: ${position}%;`"
                 @mousedown="dragging = true"
                 @touchstart.passive="dragging = true">
                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md border border-stone text-forest-light transform -translate-x-1/2 absolute">
                    <i data-lucide="chevrons-left-right" class="w-5 h-5"></i>
                </div>
            </div>
        </div>
    </div>
</section>
@endif