{{-- Block: service_area_enclave --}}
@php
    $eyebrow = $content['eyebrow'] ?? 'Areas We Serve';
    $heading = $content['heading'] ?? 'Proudly Serving the GTA';
    $supportCopy = $content['support_copy'] ?? '';
    $presentationMode = $content['presentation_mode'] ?? 'text-led';
    
    // Group cities by region if available, otherwise just list them
    $regions = $data->groupBy('region_id'); // Assuming region grouping exists, or we just display all
@endphp

<div class="max-w-7xl mx-auto py-12 lg:py-24">
    @if($presentationMode === 'tabbed-enclave')
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-24">
            <div class="w-full lg:w-1/3 text-left">
                <span class="text-luxury-label text-text-secondary block mb-3">{{ $eyebrow }}</span>
                <h2 class="text-h2 font-heading font-bold text-ink mb-6">{{ $heading }}</h2>
                <div class="w-12 h-px bg-forest/30 mb-8"></div>
                <p class="text-body-lg text-text-secondary">{{ $supportCopy }}</p>
            </div>
            
            <div class="w-full lg:w-2/3 bg-cream rounded-[2.5rem] p-10 lg:p-16 border border-stone/50 shadow-editorial">
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 lg:gap-8 text-center">
                    @foreach($data as $city)
                        <a href="{{ url('areas/'.$city->slug) }}" class="group flex flex-col items-center justify-center p-6 rounded-[1.5rem] bg-white border border-stone hover:border-forest/40 hover:shadow-luxury hover:-translate-y-1 transition-all duration-500">
                            <i data-lucide="map-pin" class="w-6 h-6 text-forest/40 mb-4 group-hover:text-forest transition-colors duration-300"></i>
                            <span class="text-sm font-bold uppercase tracking-[0.1em] text-ink">{{ $city->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        {{-- Premium Text-Led Mode --}}
        <div class="text-center max-w-4xl mx-auto mb-20 animate-on-scroll" data-animation="fade-up">
            <span class="text-luxury-label text-forest block mb-4">{{ $eyebrow }}</span>
            <h2 class="text-display font-heading font-bold text-ink leading-tight mb-8">{{ $heading }}</h2>
            <div class="w-24 h-px bg-forest/40 mx-auto mb-8"></div>
            <p class="text-xl lg:text-2xl text-text-secondary font-light leading-relaxed">{{ $supportCopy }}</p>
        </div>

        <div class="flex flex-wrap justify-center items-center gap-x-6 gap-y-4 animate-on-scroll" data-animation="fade-in" data-delay="100">
            @foreach($data as $idx => $city)
                <a href="{{ url('areas/'.$city->slug) }}" class="group flex items-center gap-3 text-lg lg:text-xl font-heading font-semibold text-text-secondary hover:text-forest transition-colors duration-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-forest/30 group-hover:bg-forest transition-colors duration-300"></span>
                    {{ $city->name }}
                </a>
            @endforeach
        </div>
    @endif
</div>