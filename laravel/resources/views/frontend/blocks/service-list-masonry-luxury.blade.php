@php
    $services = $context['services'] ?? [];
@endphp
<section class="bg-airy-gradient py-20 lg:py-32 px-5 lg:px-12 section-fade-to-white min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 lg:mb-20 gs-reveal gap-4 lg:gap-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Specialized Services' }}</p>
                <h2 class="fluid-heading text-forest word-wrap-safe">{!! $content['heading'] ?? 'Targeted<br>Execution' !!}</h2>
            </div>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light max-w-sm lg:text-right pb-2">{{ $content['description'] }}</p>
            @endif
        </div>

        @if($services && count($services) > 0)
        <div class="columns-1 md:columns-2 gap-6 lg:gap-8 space-y-6 lg:space-y-8">
            @foreach($services as $service)
            <a href="{{ url('/services/' . $service->category->slug . '/' . $service->slug) }}" class="block group break-inside-avoid gs-reveal">
                <div class="relative overflow-hidden bg-[#F4F9F4] border border-black/5 mb-4 lg:mb-6">
                    @if($service->heroMedia)
                        <img src="{{ $service->heroMedia->url }}" alt="{{ $service->name }}" class="w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]" loading="lazy" decoding="async">
                    @else
                        <div class="w-full aspect-[4/3] bg-forest/5 flex items-center justify-center"><i data-lucide="image" class="w-8 h-8 text-forest/20"></i></div>
                    @endif
                    <div class="absolute inset-0 bg-forest/80 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center mix-blend-multiply"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                        <span class="text-[10px] text-white font-bold uppercase tracking-[0.2em] border-b border-white/50 pb-1">View Specification</span>
                    </div>
                </div>
                <div class="px-2">
                    <h3 class="text-2xl lg:text-3xl font-serif text-forest group-hover:text-forest-light transition-colors mb-2">{{ $service->name }}</h3>
                    @if($service->service_summary)
                    <p class="text-ink/60 text-sm font-light leading-relaxed line-clamp-2">{{ $service->service_summary }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 border border-dashed border-stone">
            <p class="text-ink/50 text-lg font-light">No services found in this category.</p>
        </div>
        @endif

    </div>
</section>