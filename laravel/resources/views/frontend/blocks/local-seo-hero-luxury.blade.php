@php
    $page = $context['page'] ?? null;
    if (!$page) return;

    $city = $page->city;
    $service = $page->service;
    
    $title = $page->h1 ?? "{$service->name} in {$city->name}";
    $subtitle = $page->local_intro ?? '';
    
    // Attempt to grab a specific hero image, fallback to city, then service, then default
    $heroUrl = $page->heroMedia?->url 
        ?? $city->heroMedia?->url 
        ?? $service->heroMedia?->url 
        ?? 'https://images.unsplash.com/photo-1598228723654-419b48f68e4c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp';
@endphp

<section class="relative min-h-[70vh] lg:min-h-[85vh] w-full overflow-hidden flex flex-col justify-end pb-16 lg:pb-24">
    <div class="absolute inset-0 z-0 bg-forest/20">
        <img src="{{ $heroUrl }}" alt="{{ $title }}" class="w-full h-[120%] object-cover parallax-img" data-speed="0.1" loading="eager" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-forest/95 via-forest/40 to-transparent mix-blend-multiply"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-12 gs-reveal">
        <div class="flex flex-wrap items-center gap-3 lg:gap-4 text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-white/70 mb-4 lg:mb-6">
            <span class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3 h-3"></i> {{ $city->name }}, ON</span>
            <span class="w-1 h-1 bg-accent rounded-full"></span>
            <span>{{ $service->name }}</span>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-end">
            <div class="lg:col-span-7">
                <h1 class="text-4xl sm:text-5xl lg:text-[5rem] leading-[1.05] text-white font-serif text-balance drop-shadow-lg mb-6 lg:mb-0">
                    {{ $title }}
                </h1>
            </div>
            <div class="lg:col-span-5">
                @if($subtitle)
                <p class="text-white/80 text-base lg:text-lg font-light leading-relaxed border-l-2 border-accent pl-6">{{ strip_tags($subtitle) }}</p>
                @endif
                <div class="mt-8 flex gap-4">
                    <a href="#consultation" class="btn-solid text-[10px] uppercase tracking-[0.2em] font-bold px-8 py-3 bg-white text-forest hover:bg-accent hover:text-white">Book Consultation</a>
                </div>
            </div>
        </div>
    </div>
</section>