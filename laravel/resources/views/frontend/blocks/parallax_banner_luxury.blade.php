<section class="relative h-[50vh] lg:h-[80vh] overflow-hidden flex items-center justify-center">
    <img src="{{ $content['bg_image'] ?? '' }}" 
         alt="Background" class="absolute inset-0 w-full h-[130%] object-cover parallax-img" data-speed="0.15" loading="lazy" decoding="async">
    <div class="absolute inset-0 bg-forest/60 mix-blend-multiply"></div>
    
    <div class="relative z-10 text-center px-6 gs-reveal max-w-5xl mx-auto">
        <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/70 mb-4 lg:mb-6">{{ $content['eyebrow'] ?? 'The Intersection' }}</p>
        <h2 class="text-3xl sm:text-5xl lg:text-[5rem] leading-[1.05] text-white font-serif text-balance drop-shadow-lg">
            {{ $content['heading'] ?? 'Designing the Space Between' }} <br>
            @if(!empty($content['heading_highlight']))
            <span class="italic text-white/80">{{ $content['heading_highlight'] }}</span>
            @endif
        </h2>
    </div>
</section>