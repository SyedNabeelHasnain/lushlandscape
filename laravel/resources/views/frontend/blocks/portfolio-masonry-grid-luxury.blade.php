@php
    $projects = $context['projects'] ?? null;
    $categories = $context['categories'] ?? [];
    $activeCategory = $context['activeCategory'] ?? null;
@endphp
<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16 lg:mb-24 gs-reveal">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4 lg:mb-6">{{ $content['eyebrow'] ?? 'Our Work' }}</p>
            <h1 class="fluid-heading text-forest mb-6 word-wrap-safe">{!! $content['heading'] ?? 'Completed<br>Environments' !!}</h1>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light leading-relaxed">{{ $content['description'] }}</p>
            @endif
        </div>

        {{-- Filters --}}
        @if($categories && count($categories) > 0)
        <div class="flex flex-wrap justify-center gap-4 lg:gap-8 mb-16 gs-reveal">
            <a href="{{ url()->current() }}" 
               class="text-[10px] lg:text-xs font-bold uppercase tracking-[0.15em] pb-1 transition-all {{ !$activeCategory ? 'text-forest border-b-2 border-accent' : 'text-ink/40 border-b-2 border-transparent hover:text-forest' }}">
                All Projects
            </a>
            @foreach($categories as $cat)
            <a href="{{ url()->current() }}?category={{ $cat->slug }}" 
               class="text-[10px] lg:text-xs font-bold uppercase tracking-[0.15em] pb-1 transition-all {{ $activeCategory == $cat->slug ? 'text-forest border-b-2 border-accent' : 'text-ink/40 border-b-2 border-transparent hover:text-forest' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
        @endif

        {{-- Masonry Grid --}}
        @if($projects && $projects->count() > 0)
        <div class="columns-1 md:columns-2 lg:columns-3 gap-6 lg:gap-8 space-y-6 lg:space-y-8">
            @foreach($projects as $project)
            <a href="{{ url('/portfolio/' . $project->slug) }}" class="block group break-inside-avoid gs-reveal">
                <div class="relative overflow-hidden bg-[#F4F9F4] border border-black/5 mb-4">
                    @if($project->heroMedia)
                        <img src="{{ $project->heroMedia->url }}" alt="{{ $project->title }}" class="w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.03]" loading="lazy" decoding="async">
                    @else
                        <div class="w-full aspect-[4/3] bg-forest/5 flex items-center justify-center"><i data-lucide="image" class="w-8 h-8 text-forest/20"></i></div>
                    @endif
                    <div class="absolute inset-0 bg-forest/80 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-center justify-center mix-blend-multiply"></div>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                        <span class="text-[10px] text-white font-bold uppercase tracking-[0.2em] border-b border-white/50 pb-1">View Details</span>
                    </div>
                </div>
                <div class="px-2">
                    <p class="text-[9px] uppercase tracking-[0.2em] font-semibold text-accent mb-1">{{ $project->city ? $project->city->name : 'Region' }} / {{ $project->category ? $project->category->name : 'Project' }}</p>
                    <h3 class="text-xl lg:text-2xl font-serif text-forest group-hover:text-forest-light transition-colors">{{ $project->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-20 flex justify-center">
            {{ $projects->links('vendor.pagination.tailwind') }}
        </div>
        @else
        <div class="text-center py-20 border border-dashed border-stone">
            <p class="text-ink/50 text-lg font-light">No projects found matching your criteria.</p>
            <a href="{{ url()->current() }}" class="btn-outline text-[10px] uppercase tracking-[0.2em] font-bold mt-6 inline-block">Clear Filters</a>
        </div>
        @endif
    </div>
</section>