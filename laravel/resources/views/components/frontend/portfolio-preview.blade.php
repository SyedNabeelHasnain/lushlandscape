@props([
    'projects'   => collect(),
    'title'      => 'Recent Projects',
    'subtitle'   => 'A sample of our completed professional work across Our Region.',
    'viewAllUrl' => '/portfolio',
])

@if($projects->count() > 0)
<section class="section-editorial bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-24 mb-20">
            <div class="lg:col-span-7 reveal">
                <span class="text-eyebrow text-forest mb-5 block">Portfolio</span>
                <h2 class="text-h2 font-heading font-bold text-ink">{{ $title }}</h2>
                @if($subtitle)<p class="mt-5 text-text-secondary text-body-lg">{{ $subtitle }}</p>@endif
            </div>
            <div class="lg:col-span-5 flex items-end justify-end reveal">
                <a href="{{ $viewAllUrl }}" class="inline-flex items-center gap-2 text-forest text-[11px] font-semibold tracking-[0.15em] uppercase link-underline transition-colors duration-300">
                    View All Projects <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 reveal-stagger">
            @foreach($projects as $proj)
            <div class="group bg-white border border-stone overflow-hidden hover:border-forest transition-all duration-500 hover:shadow-luxury">
                <div class="aspect-4/5 overflow-hidden bg-cream relative">
                    @if($proj->heroMedia)
                    <img src="{{ $proj->heroMedia->url }}"
                         alt="{{ $proj->heroMedia->default_alt_text ?? $proj->title }}"
                         class="w-full h-full object-cover img-zoom"
                         width="600" height="750"
                         loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i data-lucide="image" class="w-12 h-12 text-stone"></i>
                    </div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/50 via-black/20 to-transparent p-8">
                        @if($proj->city)<span class="text-eyebrow text-white/70">{{ $proj->city->name }}</span>@endif
                        @if($proj->is_featured)<span class="text-eyebrow text-accent ml-3">Featured</span>@endif
                    </div>
                </div>
                <div class="p-8">
                    <h3 class="text-lg font-heading font-bold text-ink group-hover:text-forest transition-colors duration-300">{{ $proj->title }}</h3>
                    @if($proj->service)
                    <span class="text-eyebrow text-forest mt-3 block">{{ $proj->service->name }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
