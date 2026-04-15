@props([
    'before'      => null,
    'after'       => null,
    'beforeLabel' => 'Before',
    'afterLabel'  => 'After',
    'aspectRatio' => '4/3',
])

@if($before && $after)
<div
    class="relative overflow-hidden select-none cursor-col-resize border border-stone"
    style="aspect-ratio: {{ $aspectRatio }};"
    x-data="{ position: 50 }"
    x-on:mousemove="$event.buttons === 1 && handleDrag($event)"
    x-on:touchmove.prevent="handleTouch($event)"
    x-init="
        handleDrag = (e) => {
            const rect = $el.getBoundingClientRect();
            position = Math.min(100, Math.max(0, ((e.clientX - rect.left) / rect.width) * 100));
        };
        handleTouch = (e) => {
            const rect = $el.getBoundingClientRect();
            const touch = e.touches[0];
            position = Math.min(100, Math.max(0, ((touch.clientX - rect.left) / rect.width) * 100));
        };
    "
    role="img"
    :aria-label="'Before and after comparison. Divider is at ' + Math.round(position) + '% from left.'"
>
    <img src="{{ $after->url }}"
         alt="{{ $after->default_alt_text ?? $afterLabel }}"
         class="absolute inset-0 w-full h-full object-cover"
         width="800" height="600"
         loading="lazy">

    <div class="absolute inset-0 overflow-hidden" :style="`width: ${position}%`">
        <img src="{{ $before->url }}"
             alt="{{ $before->default_alt_text ?? $beforeLabel }}"
             class="absolute inset-0 w-full h-full object-cover"
             style="width: 200%"
             :style="`width: ${10000 / Math.max(position, 1)}%`"
             width="800" height="600"
             loading="lazy">
    </div>

    <div class="absolute top-0 bottom-0 w-0.5 bg-white" :style="`left: calc(${position}% - 1px)`">
        <div class="absolute top-1/2 -translate-y-1/2 -translate-x-1/2 w-10 h-10 bg-white flex items-center justify-center border border-forest">
            <i data-lucide="move-horizontal" class="w-5 h-5 text-forest"></i>
        </div>
    </div>

    <div class="absolute top-4 left-4">
        <span class="bg-black/60 text-white text-eyebrow px-3 py-1.5">{{ $beforeLabel }}</span>
    </div>
    <div class="absolute top-4 right-4">
        <span class="bg-forest text-white text-eyebrow px-3 py-1.5">{{ $afterLabel }}</span>
    </div>

    <input type="range" id="ba-slider-{{ uniqid() }}" name="comparison" min="0" max="100" x-model="position"
        class="absolute bottom-0 left-0 right-0 w-full opacity-0 h-12 cursor-col-resize"
        aria-label="Slide to compare before and after">
</div>
@endif
