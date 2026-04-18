<section id="process" class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-start gap-12 lg:gap-24 relative">
        
        <div class="w-full lg:w-5/12 lg:sticky lg:top-40 gs-reveal z-10">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Methodology' }}</p>
            <h2 class="fluid-heading text-forest mb-4 lg:mb-6 word-wrap-safe">{!! $content['heading'] ?? 'A Refined<br>Process' !!}</h2>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light max-w-sm">{{ $content['description'] }}</p>
            @endif
        </div>
        
        <div class="w-full lg:w-7/12 relative space-y-6 lg:space-y-8 z-10">
            @php
                $bgColors = ['bg-[#F9FAF9]', 'bg-[#F6F7F6]', 'bg-[#F2F4F2]', 'bg-[#EFF1EF]', 'bg-white shadow-lg'];
            @endphp
            
            @for($i = 1; $i <= 5; $i++)
                @if(!empty($content["step_{$i}_title"]))
                <div class="{{ $bgColors[$i-1] }} p-8 lg:p-10 rounded-xl border border-black/5 shadow-sm gs-reveal relative">
                    <span class="process-number">0{{ $i }}</span>
                    <p class="text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-2">{{ $content["step_{$i}_phase"] }}</p>
                    <h4 class="text-2xl lg:text-3xl font-serif text-forest mb-4">{{ $content["step_{$i}_title"] }}</h4>
                    <p class="text-ink/70 text-sm lg:text-base font-light leading-[1.7]">{{ $content["step_{$i}_desc"] }}</p>
                </div>
                @endif
            @endfor
        </div>
    </div>
</section>