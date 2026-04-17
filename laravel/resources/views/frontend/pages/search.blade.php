@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($q ? 'Search results for &quot;' . e($q) . '&quot;' : 'Search') . ' | Lush Landscape Service'"
    description="Search our landscaping services, service categories, locations, portfolio, blog posts, and FAQs."
    :canonical="url('/search') . ($q ? '?q=' . urlencode($q) : '')"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="[['label' => 'Search', 'url' => null]]" />
    </div>
</div>

<section class="bg-luxury-green-deep py-12">
    <div class="max-w-3xl mx-auto px-6 lg:px-12 text-center">
        <h1 class="text-3xl font-heading font-bold tracking-tight text-white mb-6">
            @if($q)
                Search results for <span class="text-white/80">"{{ $q }}"</span>
            @else
                Search
            @endif
        </h1>
        <form action="{{ url('/search') }}" method="GET" role="search" class="flex gap-2">
            <label for="search-page-query" class="sr-only">Search query</label>
            <input type="search" id="search-page-query" name="q" value="{{ $q }}" placeholder="Search services, cities, blog posts…"
                   class="flex-1 px-5 py-3 border border-white/20 text-text focus:outline-none focus:ring-2 focus:ring-white/50 text-base shadow-luxury"
                   aria-label="Search query" autofocus>
            <button type="submit" class="btn-luxury btn-luxury-primary shrink-0">
                Search
            </button>
        </form>
    </div>
</section>

@if($q && mb_strlen($q) >= 2)
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10 shadow-luxury">

    {{-- Filter tabs --}}
    <div class="flex flex-wrap gap-2 mb-8 border-b border-stone pb-4">
        @php
            $tabs = [
                'all'       => 'All Results (' . $total . ')',
                'services'  => 'Services (' . $services->count() . ')',
                'categories'=> 'Categories (' . $categories->count() . ')',
                'cities'    => 'Locations (' . $cities->count() . ')',
                'blog'      => 'Blog (' . $blog->count() . ')',
                'faqs'      => 'FAQs (' . $faqs->count() . ')',
                'portfolio' => 'Portfolio (' . $portfolio->count() . ')',
            ];
        @endphp
        @foreach($tabs as $key => $label)
        <a href="/search?q={{ urlencode($q) }}&type={{ $key }}"
           class="px-4 py-2 text-eyebrow font-medium transition {{ $type === $key ? 'bg-forest text-white' : 'text-text-secondary hover:bg-forest/6' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @if($total === 0)
    <div class="text-center py-20">
        <i data-lucide="search-x" class="w-16 h-16 text-stone mx-auto mb-4"></i>
        <h2 class="text-xl font-bold text-text mb-2">No results found</h2>
        <p class="text-text-secondary">Try different keywords or browse our <a href="/services" class="text-forest hover:underline">services</a> and <a href="/locations" class="text-forest hover:underline">locations</a>.</p>
    </div>
    @else

    <div class="space-y-12">

        {{-- Services --}}
        @if($services->isNotEmpty() && ($type === 'all' || $type === 'services'))
        <div>
            <h2 class="text-xl font-bold text-text mb-4 flex items-center gap-2">
                <i data-lucide="layers" class="w-5 h-5 text-forest"></i>Services
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($services as $s)
                @if($s->frontend_url)
                <a href="{{ $s->frontend_url }}" class="group flex items-start gap-3 p-4  border border-stone bg-white hover:border-forest/20 hover:shadow-sm transition">
                    @if($s->heroMedia)
                    <img src="{{ $s->heroMedia->url }}" alt="{{ $s->heroMedia->default_alt_text ?? $s->name }}"
                         class="w-14 h-14  object-cover shrink-0" width="56" height="56" loading="lazy" decoding="async">
                    @else
                    <div class="w-14 h-14  bg-forest/10 flex items-center justify-center shrink-0">
                        <i data-lucide="layers" class="w-6 h-6 text-forest/50"></i>
                    </div>
                    @endif
                    <div class="min-w-0">
                        <p class="font-semibold text-text group-hover:text-forest transition text-sm leading-snug">{{ $s->name }}</p>
                        @if($s->service_summary)
                        <p class="text-xs text-text-secondary mt-1 line-clamp-2">{{ $s->service_summary }}</p>
                        @endif
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Service Categories --}}
        @if($categories->isNotEmpty() && ($type === 'all' || $type === 'categories'))
        <div>
            <h2 class="text-xl font-bold text-text mb-4 flex items-center gap-2">
                <i data-lucide="folder-tree" class="w-5 h-5 text-forest"></i>Service Categories
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($categories as $category)
                @if($category->frontend_url)
                <a href="{{ $category->frontend_url }}" class="group flex items-start gap-3 p-4 border border-stone bg-white hover:border-forest/20 hover:shadow-sm transition">
                    @if($category->heroMedia)
                    <img src="{{ $category->heroMedia->url }}" alt="{{ $category->heroMedia->default_alt_text ?? $category->name }}"
                         class="w-14 h-14 object-cover shrink-0" width="56" height="56" loading="lazy" decoding="async">
                    @else
                    <div class="w-14 h-14 bg-forest/10 flex items-center justify-center shrink-0">
                        <i data-lucide="folder-tree" class="w-6 h-6 text-forest/50"></i>
                    </div>
                    @endif
                    <div class="min-w-0">
                        <p class="font-semibold text-text group-hover:text-forest transition text-sm leading-snug">{{ $category->name }}</p>
                        @if($category->short_description)
                        <p class="text-xs text-text-secondary mt-1 line-clamp-2">{{ $category->short_description }}</p>
                        @endif
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Locations --}}
        @if($cities->isNotEmpty() && ($type === 'all' || $type === 'cities'))
        <div>
            <h2 class="text-xl font-bold text-text mb-4 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-forest"></i>Locations
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($cities as $c)
                <a href="{{ $c->frontend_url }}" class="group flex items-center gap-2 p-3  border border-stone bg-white hover:border-forest/20 hover:shadow-sm transition">
                    <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
                    <span class="text-sm font-medium text-text group-hover:text-forest transition">{{ $c->name }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Blog --}}
        @if($blog->isNotEmpty() && ($type === 'all' || $type === 'blog'))
        <div>
            <h2 class="text-xl font-bold text-text mb-4 flex items-center gap-2">
                <i data-lucide="newspaper" class="w-5 h-5 text-forest"></i>Blog Posts
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($blog as $post)
                <a href="{{ $post->frontend_url }}" class="group flex gap-4 p-4  border border-stone bg-white hover:border-forest/20 hover:shadow-sm transition">
                    @if($post->heroMedia)
                    <img src="{{ $post->heroMedia->url }}" alt="{{ $post->heroMedia->default_alt_text ?? $post->title }}"
                         class="w-20 h-20  object-cover shrink-0" width="80" height="80" loading="lazy" decoding="async">
                    @endif
                    <div class="min-w-0">
                        <p class="font-semibold text-text group-hover:text-forest transition text-sm leading-snug">{{ $post->title }}</p>
                        @if($post->excerpt)
                        <p class="text-xs text-text-secondary mt-1 line-clamp-2">{{ $post->excerpt }}</p>
                        @endif
                        <p class="text-xs text-text-secondary/60 mt-1.5">{{ $post->published_at?->format('M j, Y') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- FAQs --}}
        @if($faqs->isNotEmpty() && ($type === 'all' || $type === 'faqs'))
        <div>
            <h2 class="text-xl font-bold text-text mb-4 flex items-center gap-2">
                <i data-lucide="help-circle" class="w-5 h-5 text-forest"></i>FAQs
            </h2>
            <div class="space-y-3">
                @foreach($faqs as $faq)
                @if($faq->frontend_url)
                <a href="{{ $faq->frontend_url }}" class="block p-4  border border-stone bg-white hover:border-forest/20 hover:shadow-sm transition">
                    <p class="font-semibold text-text text-sm mb-2">{{ $faq->question }}</p>
                    <p class="text-sm text-text-secondary line-clamp-3">{{ strip_tags($faq->answer) }}</p>
                </a>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        {{-- Portfolio --}}
        @if($portfolio->isNotEmpty() && ($type === 'all' || $type === 'portfolio'))
        <div>
            <h2 class="text-xl font-bold text-text mb-4 flex items-center gap-2">
                <i data-lucide="image" class="w-5 h-5 text-forest"></i>Portfolio Projects
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($portfolio as $proj)
                @if($proj->frontend_url)
                <a href="{{ $proj->frontend_url }}" class="group block  overflow-hidden border border-stone bg-white hover:shadow-sm transition">
                    @if($proj->heroMedia)
                    <img src="{{ $proj->heroMedia->url }}" alt="{{ $proj->heroMedia->default_alt_text ?? $proj->title }}"
                         class="w-full aspect-video object-cover group-hover:scale-105 transition duration-300" width="400" height="225" loading="lazy" decoding="async">
                    @else
                    <div class="w-full aspect-video bg-cream flex items-center justify-center">
                        <i data-lucide="image" class="w-8 h-8 text-stone"></i>
                    </div>
                    @endif
                    <div class="p-3">
                        <p class="font-semibold text-text group-hover:text-forest transition text-sm">{{ $proj->title }}</p>
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        </div>
        @endif

    </div>
    @endif
</div>
@else
<div class="max-w-3xl mx-auto px-6 lg:px-12 py-20 text-center">
    <i data-lucide="search" class="w-16 h-16 text-stone mx-auto mb-4"></i>
    <p class="text-text-secondary">Enter at least 2 characters to search.</p>
</div>
@endif

@endsection
