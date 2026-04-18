<section class="bg-white py-20 lg:py-32 px-5 lg:px-6 overflow-hidden section-fade-to-airy">
    <div class="max-w-4xl mx-auto text-center gs-reveal">
        @if(!empty($content['eyebrow']))
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4 lg:mb-6">{{ $content['eyebrow'] }}</p>
        @endif
        
        <h2 class="fluid-heading text-forest mb-8 lg:mb-10 word-wrap-safe">
            {{ $content['heading'] ?? 'Built for Properties Where' }}<br>
            @if(!empty($content['heading_highlight']))
            <span class="italic text-forest-light">{{ $content['heading_highlight'] }}</span>
            @endif
        </h2>
        
        @if(!empty($content['paragraph']))
        <p class="text-ink/70 text-base sm:text-lg lg:text-xl font-light leading-relaxed">
            {{ $content['paragraph'] }}
        </p>
        @endif
    </div>
</section>