@php
    $imagePosition = $content['image_position'] ?? 'left';
    $isImageLeft = $imagePosition === 'left';
@endphp
<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row {{ $isImageLeft ? '' : 'lg:flex-row-reverse' }} items-center gap-12 lg:gap-20">
        
        {{-- Image Side --}}
        <div class="w-full lg:w-1/2 gs-reveal">
            <div class="relative w-full aspect-[4/5] lg:aspect-square bg-forest/5 overflow-hidden shadow-2xl">
                <img src="{{ $content['image'] ?? 'https://images.unsplash.com/photo-1600607686527-6fb886090705?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp' }}" 
                     alt="Philosophy" 
                     class="w-full h-full object-cover parallax-img" data-speed="0.05" loading="lazy" decoding="async">
            </div>
        </div>

        {{-- Text Side --}}
        <div class="w-full lg:w-1/2 gs-reveal">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4 lg:mb-6">{{ $content['eyebrow'] ?? 'Our Philosophy' }}</p>
            <h2 class="fluid-heading text-forest mb-6 lg:mb-8 word-wrap-safe">
                {{ $content['heading'] ?? 'Built for Properties Where' }}<br>
                @if(!empty($content['heading_highlight']))
                <span class="italic text-forest-light">{{ $content['heading_highlight'] }}</span>
                @endif
            </h2>
            
            <div class="space-y-6 text-ink/70 text-base lg:text-lg font-light leading-relaxed">
                @if(!empty($content['paragraph_1']))
                <p>{{ $content['paragraph_1'] }}</p>
                @endif
                @if(!empty($content['paragraph_2']))
                <p>{{ $content['paragraph_2'] }}</p>
                @endif
            </div>

            @if(!empty($content['signature_name']))
            <div class="mt-10 lg:mt-12 pt-8 border-t border-black/5">
                <p class="font-serif text-xl lg:text-2xl text-forest">{{ $content['signature_name'] }}</p>
                @if(!empty($content['signature_title']))
                <p class="text-[10px] uppercase tracking-[0.2em] font-semibold text-accent mt-2">{{ $content['signature_title'] }}</p>
                @endif
            </div>
            @endif
        </div>
        
    </div>
</section>