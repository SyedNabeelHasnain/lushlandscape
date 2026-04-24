@props([
    'posts'    => collect(),
    'title'    => 'Landscaping Insights',
    'subtitle' => 'Expert tips, cost guides, and project inspiration for Our Region homeowners.',
    'viewAllUrl' => '/blog',
])

@if($posts->count() > 0)
<section class="section-editorial bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-24 mb-20">
            <div class="lg:col-span-7 reveal">
                <span class="text-eyebrow text-forest mb-5 block">Blog</span>
                <h2 class="text-h2 font-heading font-bold text-ink">{{ $title }}</h2>
                @if($subtitle)<p class="mt-5 text-text-secondary text-body-lg">{{ $subtitle }}</p>@endif
            </div>
            <div class="lg:col-span-5 flex items-end justify-end reveal">
                <a href="{{ $viewAllUrl }}" class="inline-flex items-center gap-2 text-forest text-[11px] font-semibold tracking-[0.15em] uppercase link-underline">
                    View All Articles <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal-stagger">
            @foreach($posts->take(3) as $post)
            <a href="{{ url('/blog/' . $post->slug) }}" class="group bg-white border border-stone overflow-hidden hover:border-forest transition-all duration-500 hover:shadow-luxury">
                <div class="aspect-16/10 overflow-hidden bg-cream">
                    @if($post->heroMedia ?? null)
                    <img src="{{ $post->heroMedia->url }}"
                         alt="{{ $post->heroMedia->default_alt_text ?? $post->title }}"
                         class="w-full h-full object-cover img-zoom"
                         width="600" height="375"
                         loading="lazy">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i data-lucide="file-text" class="w-10 h-10 text-stone"></i>
                    </div>
                    @endif
                </div>
                <div class="p-10">
                    <div class="flex items-center gap-3 mb-5">
                        @if($post->category ?? null)<span class="text-eyebrow text-forest">{{ $post->category->name }}</span>@endif
                        <span class="text-xs text-text-secondary">{{ $post->published_at?->format('M j, Y') }}</span>
                    </div>
                    <h3 class="text-lg font-heading font-bold text-ink group-hover:text-forest transition-colors duration-300 line-clamp-2 leading-snug">{{ $post->title }}</h3>
                    @if($post->excerpt)<p class="mt-4 text-sm text-text-secondary line-clamp-2 leading-relaxed">{{ $post->excerpt }}</p>@endif
                    <div class="mt-6 flex items-center gap-2 text-forest text-[11px] font-semibold tracking-[0.15em] uppercase">
                        Read Article <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1.5 transition-transform duration-400"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
