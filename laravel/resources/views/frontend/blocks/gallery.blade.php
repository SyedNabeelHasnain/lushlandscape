@php
    $images  = $content['images'] ?? [];
    $cols    = $content['columns'] ?? '3';
    $aspect  = $content['aspect'] ?? 'square';
    $gapCls  = match($content['gap'] ?? 'md') { 'none' => 'gap-0', 'sm' => 'gap-1', default => 'gap-3 md:gap-4' };
    $gridCls = match($cols) { '2' => 'grid-cols-2', '4' => 'grid-cols-2 md:grid-cols-4', default => 'grid-cols-2 md:grid-cols-3' };
    $aspectCls = match($aspect) { 'landscape' => 'aspect-4/3', 'portrait' => 'aspect-3/4', 'auto' => '', default => 'aspect-square' };
@endphp
@if(!empty($images))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
    @if(!empty($content['heading']))
    <h2 class="text-h3 font-heading font-bold text-forest mb-10">{{ $content['heading'] }}</h2>
    @endif
    <div class="grid {{ $gridCls }} {{ $gapCls }} reveal-stagger" x-data="{ lightbox: null }">
        @foreach($images as $i => $img)
        @php $asset = !empty($img['media_id']) ? ($mediaLookup[$img['media_id']] ?? null) : null; @endphp
        @if($asset)
        <button type="button" x-on:click="lightbox = {{ $i }}"
                class="relative overflow-hidden group focus:outline-none focus:ring-2 focus:ring-forest/50"
                aria-label="View image {{ $i + 1 }} in lightbox">
            <x-frontend.media
                :asset="$asset"
                :alt="$img['alt'] ?? ''"
                class="w-full {{ $aspectCls }} object-cover img-zoom"
            />
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-500 flex items-center justify-center">
                <i data-lucide="maximize-2" class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></i>
            </div>
        </button>
        @endif
        @endforeach

        <div x-show="lightbox !== null" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-black/95 backdrop-blur-sm flex items-center justify-center p-4"
             x-on:keydown.escape.window="lightbox = null" x-on:click.self="lightbox = null">
            <button type="button" x-on:click="lightbox = null" class="absolute top-6 right-6 text-white/50 hover:text-white z-10 transition-colors duration-300" aria-label="Close lightbox">
                <i data-lucide="x" class="w-7 h-7"></i>
            </button>
            <button type="button" x-on:click="lightbox = (lightbox - 1 + {{ count($images) }}) % {{ count($images) }}"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all duration-300"
                    aria-label="Previous image">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>
            <button type="button" x-on:click="lightbox = (lightbox + 1) % {{ count($images) }}"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all duration-300"
                    aria-label="Next image">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>
            @foreach($images as $i => $img)
            @php $asset = !empty($img['media_id']) ? ($mediaLookup[$img['media_id']] ?? null) : null; @endphp
            @if($asset)
            <div x-show="lightbox === {{ $i }}" x-cloak x-transition class="max-w-5xl w-full text-center">
                <x-frontend.media :asset="$asset" :alt="$img['alt'] ?? ''" class="max-h-[80vh] mx-auto object-contain" />
                @if(!empty($img['caption']))
                <p class="mt-4 text-sm text-white/50">{{ $img['caption'] }}</p>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endif
