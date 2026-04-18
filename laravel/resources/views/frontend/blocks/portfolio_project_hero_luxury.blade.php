@php
    $project = $context['project'] ?? null;
    $heroUrl = $project && $project->heroMedia ? $project->heroMedia->url : 'https://images.unsplash.com/photo-1600607686527-6fb886090705?ixlib=rb-4.0.3&auto=format&fit=crop&w=2500&q=80&fm=webp';
    $title = $project ? $project->title : 'Project Title';
    $city = $project && $project->city ? $project->city->name : 'Greater Toronto Area';
    $service = $project && $project->service ? $project->service->name : 'Luxury Landscaping';
    $category = $project && $project->category ? $project->category->name : 'Estate Project';
@endphp
<section class="relative h-[60vh] lg:h-[80vh] w-full overflow-hidden flex items-end pb-12 lg:pb-20">
    <div class="absolute inset-0 z-0 bg-forest/20">
        <img src="{{ $heroUrl }}" alt="{{ $title }}" class="w-full h-[120%] object-cover parallax-img" data-speed="0.1" loading="eager" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent mix-blend-multiply"></div>
    </div>
    
    <div class="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-12 gs-reveal">
        <div class="flex items-center gap-4 text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-white/70 mb-4 lg:mb-6">
            <span>{{ $city }}</span>
            <span class="w-1 h-1 bg-accent rounded-full"></span>
            <span>{{ $category }}</span>
        </div>
        <h1 class="text-4xl sm:text-5xl lg:text-[5rem] leading-[1.05] text-white font-serif text-balance drop-shadow-lg max-w-4xl">
            {{ $title }}
        </h1>
    </div>
</section>