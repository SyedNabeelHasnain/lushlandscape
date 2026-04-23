@php
    $page = $context['page'] ?? null;
    $city = $page ? $page->city : null;
    $cityName = $city ? $city->name : 'Region';
    
    // Get projects tagged to this specific city
    // In a real app, this might come pre-loaded in the context
    $projects = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published')
        ->when($city, function($q) use ($city) {
            return $q->where('city_id', $city->id);
        })
        ->with(['category', 'heroMedia'])
        ->latest('completion_date')
        ->take(6)
        ->get();
        
    $heading = str_replace('[City]', $cityName, $content['heading'] ?? 'Recent Installations in [City]');
@endphp

@if($projects->count() > 0)
<section class="bg-white py-20 lg:py-32 overflow-hidden section-fade-to-airy">
    <div class="portfolio-pin-wrapper w-full lg:h-[90vh] flex flex-col justify-center py-10 lg:py-0">
        
        <div class="max-w-7xl w-full mx-auto px-6 lg:px-12 mb-8 lg:mb-16">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4 lg:gap-6 gs-reveal">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Local Portfolio' }}</p>
                    <h2 class="fluid-heading text-forest word-wrap-safe">{{ $heading }}</h2>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ url('/portfolio?city=' . ($city->slug ?? '')) }}" class="text-[10px] lg:text-[11px] font-bold uppercase tracking-[0.15em] text-forest-light border-b border-forest-light pb-1 hover:text-accent hover:border-accent transition-colors">View All {{ $cityName }} Projects</a>
                    <div class="flex items-center gap-2 ml-4">
                        <button id="portfolio-prev" class="portfolio-nav-btn w-9 h-9 lg:w-10 lg:h-10 rounded-full border border-accent/25 flex items-center justify-center text-accent hover:bg-accent hover:text-white transition-all" aria-label="Previous"><i data-lucide="arrow-left" class="w-4 h-4"></i></button>
                        <button id="portfolio-next" class="portfolio-nav-btn w-9 h-9 lg:w-10 lg:h-10 rounded-full border border-accent/25 flex items-center justify-center text-accent hover:bg-accent hover:text-white transition-all" aria-label="Next"><i data-lucide="arrow-right" class="w-4 h-4"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-swipe flex gap-5 lg:gap-10 px-6 lg:px-12 w-full lg:w-max no-scrollbar overflow-x-auto lg:overflow-x-visible snap-x snap-mandatory lg:snap-none">
            @foreach($projects as $index => $project)
            <div class="mobile-swipe-item w-[85vw] sm:w-[50vw] lg:w-[35vw] flex-shrink-0 group cursor-pointer snap-center gs-reveal {{ $loop->last ? 'lg:pr-12' : '' }}">
                <div class="overflow-hidden mb-4 lg:mb-6 border border-black/5 bg-[#F4F9F4] aspect-[4/5] lg:aspect-[3/4]">
                    @if($project->heroMedia)
                        <img src="{{ $project->heroMedia->url }}" alt="{{ $project->title }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]" loading="lazy" decoding="async">
                    @else
                        <div class="w-full h-full bg-forest/5 flex items-center justify-center"><i data-lucide="image" class="w-8 h-8 text-forest/20"></i></div>
                    @endif
                </div>
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.2em] font-semibold text-accent mb-1 lg:mb-2">0{{ $index+1 }} / {{ $project->neighborhood ?? $cityName }}</p>
                        <h3 class="text-xl lg:text-3xl font-serif text-forest">{{ $project->title }}</h3>
                    </div>
                    <a href="{{ url('/portfolio/' . $project->slug) }}" class="group-hover:translate-x-2 transition-transform duration-500 mt-2 block">
                        <i data-lucide="arrow-right" class="w-4 h-4 text-forest-light -rotate-45 group-hover:rotate-0 transition-transform duration-500"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif