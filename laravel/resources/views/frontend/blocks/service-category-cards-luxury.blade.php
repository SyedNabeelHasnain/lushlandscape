@php
    $categories = $context['categories'] ?? [];
@endphp
<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy min-h-screen">
    <div class="max-w-7xl mx-auto">
        
        <div class="text-center max-w-3xl mx-auto mb-16 lg:mb-24 gs-reveal">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4 lg:mb-6">{{ $content['eyebrow'] ?? 'Our Capabilities' }}</p>
            <h1 class="fluid-heading text-forest mb-6 word-wrap-safe">{!! $content['heading'] ?? 'Architectural<br>Disciplines' !!}</h1>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light leading-relaxed">{{ $content['description'] }}</p>
            @endif
        </div>

        @if($categories && count($categories) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10">
            @foreach($categories as $category)
            @php
                // Try to find a hero image from the first service in the category, or use a default
                $firstService = $category->services->first();
                $heroUrl = $firstService && $firstService->heroMedia ? $firstService->heroMedia->url : 'https://images.unsplash.com/photo-1591825729269-caeb344f6df2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80&fm=webp';
            @endphp
            <a href="{{ url('/services/' . $category->slug_final) }}" class="group relative block w-full aspect-square md:aspect-[4/5] lg:aspect-[3/4] overflow-hidden border border-black/5 bg-[#F4F9F4] gs-reveal">
                
                {{-- Background Image with Scale Effect --}}
                <img src="{{ $heroUrl }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[1.5s] ease-out group-hover:scale-[1.05]" loading="lazy" decoding="async">
                
                {{-- Default Gradient (Darken bottom) --}}
                <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/20 to-transparent mix-blend-multiply opacity-80 group-hover:opacity-100 transition-opacity duration-500"></div>
                
                {{-- Hover Overlay (Solid Deep Green) --}}
                <div class="absolute inset-0 bg-forest/90 opacity-0 group-hover:opacity-90 transition-opacity duration-500 flex flex-col justify-center items-center text-center p-8 lg:p-12">
                    <p class="text-[10px] text-accent font-bold uppercase tracking-[0.2em] mb-4 transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 delay-100">Explore Discipline</p>
                    <p class="text-white/80 font-light leading-relaxed text-sm lg:text-base transform translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 delay-200">
                        {{ $category->description ?? 'View our specialized execution in ' . strtolower($category->name) . '.' }}
                    </p>
                </div>

                {{-- Title (Always visible at bottom, moves up on hover) --}}
                <div class="absolute bottom-0 left-0 w-full p-8 lg:p-10 flex flex-col transition-transform duration-500 group-hover:-translate-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl lg:text-4xl font-serif text-white">{{ $category->name }}</h3>
                        <div class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white backdrop-blur-sm bg-white/5 group-hover:bg-white group-hover:text-forest transition-colors duration-500">
                            <i class="fa-solid fa-arrow-right text-sm -rotate-45 group-hover:rotate-0 transition-transform duration-500"></i>
                        </div>
                    </div>
                    
                    {{-- Service List Preview --}}
                    <div class="mt-6 overflow-hidden max-h-0 group-hover:max-h-40 transition-all duration-700 ease-in-out">
                        <ul class="space-y-2 text-white/70 text-xs lg:text-sm font-light">
                            @foreach($category->services->take(4) as $service)
                            <li class="flex items-center gap-2">
                                <span class="w-1 h-1 bg-accent rounded-full"></span> {{ $service->name }}
                            </li>
                            @endforeach
                            @if($category->services->count() > 4)
                            <li class="text-accent italic pt-1">+ {{ $category->services->count() - 4 }} more</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-20 border border-dashed border-stone">
            <p class="text-ink/50 text-lg font-light">No service categories found in the CMS.</p>
        </div>
        @endif

    </div>
</section>