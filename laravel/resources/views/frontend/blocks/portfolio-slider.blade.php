<section id="portfolio" class="bg-white relative overflow-hidden section-fade-to-airy">
    <div class="portfolio-pin-wrapper w-full lg:h-screen flex flex-col justify-center py-20 lg:py-0">
        
        <div class="max-w-7xl w-full mx-auto px-6 lg:px-12 mb-8 lg:mb-16">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4 lg:gap-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-ink/40 mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Completed Works' }}</p>
                    <h2 class="fluid-heading text-forest word-wrap-safe">{{ $content['heading'] ?? 'Selected Portfolio' }}</h2>
                </div>
                <div class="flex items-center gap-4">
                    @if(!empty($content['link_url']))
                    <a href="{{ $content['link_url'] }}" class="text-[10px] lg:text-[11px] font-bold uppercase tracking-[0.15em] text-forest-light border-b border-forest-light pb-1 hover:text-accent hover:border-accent transition-colors">{{ $content['link_text'] ?? 'Explore All Cases' }}</a>
                    @endif
                    <div class="flex items-center gap-2 ml-4">
                        <button id="portfolio-prev" class="portfolio-nav-btn w-9 h-9 lg:w-10 lg:h-10 rounded-full border border-accent/25 flex items-center justify-center text-accent hover:bg-accent hover:text-white transition-all" aria-label="Previous"><i data-lucide="arrow-left" class="w-4 h-4"></i></button>
                        <button id="portfolio-next" class="portfolio-nav-btn w-9 h-9 lg:w-10 lg:h-10 rounded-full border border-accent/25 flex items-center justify-center text-accent hover:bg-accent hover:text-white transition-all" aria-label="Next"><i data-lucide="arrow-right" class="w-4 h-4"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div id="portfolio-track" class="mobile-swipe flex gap-5 lg:gap-10 px-6 lg:px-12 w-full lg:w-max no-scrollbar">
            @for($i = 1; $i <= 4; $i++)
                @if(!empty($content["item_{$i}_img"]))
                <div class="mobile-swipe-item w-[85vw] sm:w-[50vw] lg:w-[35vw] flex-shrink-0 group cursor-pointer {{ $i == 4 ? 'lg:pr-12' : '' }}">
                    <div class="overflow-hidden mb-4 lg:mb-6 border border-black/5 bg-[#F4F9F4] aspect-video lg:aspect-[3/2]">
                        <img src="{{ $content["item_{$i}_img"] }}" alt="Portfolio item" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]" loading="lazy" decoding="async">
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.2em] font-semibold text-accent mb-1 lg:mb-2">{{ $content["item_{$i}_eyebrow"] }}</p>
                            <h3 class="text-xl lg:text-3xl font-serif text-forest">{{ $content["item_{$i}_title"] }}</h3>
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-forest-light -rotate-45 group-hover:rotate-0 transition-transform duration-500 mt-2"></i>
                    </div>
                </div>
                @endif
            @endfor
        </div>
    </div>
</section>