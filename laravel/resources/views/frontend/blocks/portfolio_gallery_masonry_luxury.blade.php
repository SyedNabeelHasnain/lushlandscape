@php
    $gallery = $context['gallery'] ?? [];
@endphp
@if($gallery && count($gallery) > 0)
<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy">
    <div class="max-w-7xl mx-auto gs-reveal">
        
        <div class="mb-12 lg:mb-16 border-b border-stone pb-4">
            <h3 class="text-2xl lg:text-3xl font-serif text-forest">{{ $content['heading'] ?? 'Visual Documentation' }}</h3>
        </div>

        <div class="columns-1 sm:columns-2 lg:columns-3 gap-4 lg:gap-6 space-y-4 lg:space-y-6">
            @foreach($gallery as $media)
            <div class="break-inside-avoid gs-reveal group overflow-hidden bg-[#F4F9F4] border border-black/5 relative cursor-zoom-in"
                 x-data="{ showModal: false }"
                 @click="showModal = true">
                 
                <img src="{{ $media->url }}" alt="Project documentation image" class="w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]" loading="lazy" decoding="async">
                
                {{-- Overlay --}}
                <div class="absolute inset-0 bg-forest/80 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center mix-blend-multiply"></div>
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                    <i data-lucide="expand" class="w-8 h-8 text-white/70"></i>
                </div>

                {{-- Lightbox Modal --}}
                <template x-teleport="body">
                    <div x-show="showModal" 
                         x-transition.opacity.duration.300ms
                         class="fixed inset-0 z-[200] bg-black/95 backdrop-blur-sm flex items-center justify-center p-4"
                         @click.self="showModal = false"
                         @keydown.escape.window="showModal = false">
                        
                        <button @click="showModal = false" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors focus:outline-none z-[210]">
                            <i data-lucide="x" class="w-8 h-8"></i>
                        </button>
                        
                        <img src="{{ $media->url }}" alt="Expanded project view" class="max-w-full max-h-[90vh] object-contain shadow-2xl rounded-sm">
                    </div>
                </template>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif