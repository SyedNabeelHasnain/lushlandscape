<section class="bg-airy-gradient py-20 lg:py-32 px-5 lg:px-6 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 lg:mb-16 gs-reveal gap-4 lg:gap-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Core Disciplines' }}</p>
                <h2 class="fluid-heading text-forest word-wrap-safe">{!! $content['heading'] ?? 'Architectural<br>Solutions' !!}</h2>
            </div>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light max-w-sm lg:text-right pb-2">{{ $content['description'] }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
            @for($i = 1; $i <= 4; $i++)
                @if(!empty($content["card_{$i}_title"]))
                <div class="bg-white p-8 lg:p-10 border border-black/5 hover:border-accent transition-colors duration-500 gs-reveal shadow-sm">
                    <i class="{{ $content["card_{$i}_icon"] ?? 'fa-solid fa-vector-square' }} text-2xl text-forest-light mb-5 lg:mb-6" aria-hidden="true"></i>
                    <h3 class="text-2xl font-serif text-forest mb-3 lg:mb-4">{{ $content["card_{$i}_title"] }}</h3>
                    <p class="text-ink/60 text-sm leading-[1.7] mb-5 lg:mb-6 font-light">{{ $content["card_{$i}_desc"] }}</p>
                    
                    @if(!empty($content["card_{$i}_list"]))
                    <ul class="text-[10px] uppercase tracking-[0.15em] font-semibold text-accent space-y-2">
                        @foreach(explode(',', $content["card_{$i}_list"]) as $item)
                        <li>{{ trim($item) }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endif
            @endfor

            {{-- Slider Component --}}
            <div class="md:col-span-1 lg:col-span-2 relative overflow-hidden bg-forest border border-black/5 gs-reveal shadow-sm min-h-[280px] lg:min-h-[300px]" 
                 x-data="{ active: 1, timer: null }" 
                 x-init="timer = setInterval(() => { active = active === 1 ? 2 : 1 }, 5000)">
                <div class="absolute inset-0 z-0">
                    <img src="{{ $content['slider_img_1'] ?? '' }}" class="w-full h-full object-cover absolute inset-0 transition-opacity duration-700 ease-out" :class="active === 1 ? 'opacity-50' : 'opacity-0'" alt="Detail 1" loading="lazy" decoding="async">
                    <img src="{{ $content['slider_img_2'] ?? '' }}" class="w-full h-full object-cover absolute inset-0 transition-opacity duration-700 ease-out" :class="active === 2 ? 'opacity-50' : 'opacity-0'" alt="Detail 2" loading="lazy" decoding="async">
                </div>
                
                <div class="absolute top-1/2 left-4 -translate-y-1/2 z-20">
                    <button @click="active = active === 1 ? 2 : 1; clearInterval(timer); timer = setInterval(() => { active = active === 1 ? 2 : 1 }, 5000)" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/30 backdrop-blur-md flex items-center justify-center text-white transition-all focus:outline-none" aria-label="Previous image"><i class="fa-solid fa-chevron-left text-xs"></i></button>
                </div>
                <div class="absolute top-1/2 right-4 -translate-y-1/2 z-20">
                    <button @click="active = active === 1 ? 2 : 1; clearInterval(timer); timer = setInterval(() => { active = active === 1 ? 2 : 1 }, 5000)" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/30 backdrop-blur-md flex items-center justify-center text-white transition-all focus:outline-none" aria-label="Next image"><i class="fa-solid fa-chevron-right text-xs"></i></button>
                </div>

                <div class="absolute inset-0 z-10 p-8 lg:p-10 flex flex-col justify-end pointer-events-none">
                    <div class="flex flex-col md:flex-row justify-between md:items-end w-full gap-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/50 mb-2">{{ $content['slider_eyebrow'] ?? 'Material Integrity' }}</p>
                            <h3 class="text-2xl lg:text-3xl font-serif text-white max-w-sm leading-tight drop-shadow-md">{{ $content['slider_heading'] ?? 'Crafted with architectural precision.' }}</h3>
                        </div>
                        <div class="flex gap-2 lg:gap-3 mt-2 md:mt-0">
                            <div class="w-6 lg:w-8 h-[2px] transition-colors duration-500" :class="active === 1 ? 'bg-white' : 'bg-white/30'"></div>
                            <div class="w-6 lg:w-8 h-[2px] transition-colors duration-500" :class="active === 2 ? 'bg-white' : 'bg-white/30'"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>