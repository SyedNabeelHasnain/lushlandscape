<section class="bg-forest py-20 lg:py-32 px-5 lg:px-12 section-fade-to-dark overflow-hidden relative">
    <div class="absolute inset-0 opacity-[0.03] mix-blend-multiply pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
    
    <div class="max-w-7xl mx-auto relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 lg:mb-24 gs-reveal">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4 lg:mb-6">{{ $content['eyebrow'] ?? 'The Firm' }}</p>
            <h2 class="fluid-heading text-white mb-6 word-wrap-safe">{{ $content['heading'] ?? 'Institutional Grade Security' }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12 lg:gap-y-20">
            @for($i = 1; $i <= 4; $i++)
                @if(!empty($content["cred_{$i}_title"]))
                <div class="flex flex-col items-center text-center gs-reveal">
                    <div class="w-16 h-16 lg:w-20 lg:h-20 rounded-full border border-white/20 bg-white/5 backdrop-blur-md flex items-center justify-center mb-6 lg:mb-8 text-accent">
                        <i data-lucide="{{ $content["cred_{$i}_icon"] ?? 'shield-check' }}" class="w-8 h-8 lg:w-10 lg:h-10"></i>
                    </div>
                    <h3 class="text-xl lg:text-2xl font-serif text-white mb-4 lg:mb-5">{{ $content["cred_{$i}_title"] }}</h3>
                    <p class="text-white/70 text-sm lg:text-base font-light leading-relaxed max-w-sm">{{ $content["cred_{$i}_desc"] }}</p>
                </div>
                @endif
            @endfor
        </div>
    </div>
</section>