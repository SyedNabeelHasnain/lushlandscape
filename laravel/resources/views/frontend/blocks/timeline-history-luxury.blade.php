<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy overflow-hidden">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-start gap-12 lg:gap-24 relative">
        
        <div class="w-full lg:w-5/12 lg:sticky lg:top-40 gs-reveal z-10">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Our Evolution' }}</p>
            <h2 class="fluid-heading text-forest mb-4 lg:mb-6 word-wrap-safe">{!! $content['heading'] ?? 'A Heritage of<br>Craftsmanship' !!}</h2>
        </div>
        
        <div class="w-full lg:w-7/12 relative space-y-16 lg:space-y-24 z-10 pl-6 lg:pl-10 border-l border-forest/10">
            @for($i = 1; $i <= 3; $i++)
                @if(!empty($content["year_{$i}"]))
                <div class="relative gs-reveal">
                    {{-- Timeline Dot --}}
                    <div class="absolute -left-[1.9rem] lg:-left-[2.9rem] top-1.5 w-4 h-4 rounded-full bg-accent border-4 border-white shadow-sm z-20"></div>
                    
                    <span class="text-[10px] lg:text-xs font-bold uppercase tracking-[0.2em] text-forest/40 mb-3 block">{{ $content["year_{$i}"] }}</span>
                    <h3 class="text-2xl lg:text-3xl font-serif text-forest mb-4 lg:mb-6">{{ $content["title_{$i}"] }}</h3>
                    <p class="text-ink/70 text-base lg:text-lg font-light leading-relaxed max-w-xl">{{ $content["desc_{$i}"] }}</p>
                </div>
                @endif
            @endfor
        </div>
    </div>
</section>