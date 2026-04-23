@php
    $cities = $context['cities'] ?? collect();
@endphp
<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 lg:mb-20 gs-reveal gap-4 lg:gap-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Service Footprint' }}</p>
                <h2 class="fluid-heading text-forest word-wrap-safe">{!! $content['heading'] ?? 'Geographical<br>Execution' !!}</h2>
            </div>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light max-w-sm lg:text-right pb-2">{{ $content['description'] }}</p>
            @endif
        </div>

        @if($cities->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach($cities as $city)
            <a href="{{ url('/professional-' . $city->slug) }}" class="group relative block aspect-[4/3] overflow-hidden bg-forest/5 border border-black/5 gs-reveal break-inside-avoid">
                @if($city->heroMedia)
                    <img src="{{ $city->heroMedia->url }}" alt="{{ $city->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] ease-out group-hover:scale-[1.05]" loading="lazy" decoding="async">
                @else
                    <div class="absolute inset-0 bg-forest/10 flex items-center justify-center"><i data-lucide="map-pin" class="w-8 h-8 text-forest/20"></i></div>
                @endif
                
                {{-- Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/40 to-transparent mix-blend-multiply opacity-80 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                {{-- Content --}}
                <div class="absolute inset-0 p-6 lg:p-8 flex flex-col justify-end">
                    <div class="transform transition-transform duration-500 group-hover:-translate-y-2">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-2xl lg:text-3xl font-serif text-white">{{ $city->name }}</h3>
                            <i class="fa-solid fa-arrow-right text-white/50 group-hover:text-white -rotate-45 group-hover:rotate-0 transition-all duration-500"></i>
                        </div>
                        <div class="overflow-hidden max-h-0 group-hover:max-h-10 transition-all duration-500 ease-in-out">
                            <p class="text-[10px] text-accent font-bold uppercase tracking-[0.15em] pt-2">
                                @if($city->active_service_pages_count > 0)
                                    {{ $city->active_service_pages_count }} Specialized Services
                                @else
                                    Explore Service Area
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 border border-dashed border-stone">
            <p class="text-ink/50 text-lg font-light">No active cities found in the CMS.</p>
        </div>
        @endif

    </div>
</section>