{{-- Block: blog_directory --}}
@php
    $postsSource = $context['posts'] ?? $data;
    $postsCollection = method_exists($postsSource, 'getCollection')
        ? $postsSource->getCollection()
        : collect($postsSource);
    $categories = collect($context['categories'] ?? []);
    $currentCategory = $context['category'] ?? null;
    $isCategoryPage = $currentCategory !== null;
    $eyebrow = $content['eyebrow'] ?? ($isCategoryPage ? 'Blog Category' : '');
    $heading = $content['heading'] ?? ($isCategoryPage ? $currentCategory->name : 'Landscaping Blog');
    $subtitle = $content['subtitle'] ?? ($isCategoryPage
        ? ($currentCategory->short_description ?? '')
        : 'Expert tips, cost guides, and project inspiration for Our Region homeowners.');
    $tone = $content['tone'] ?? 'light';
    $showFeaturedHero = (bool) ($content['show_featured_hero'] ?? true);
    $showCategoryTabs = (bool) ($content['show_category_tabs'] ?? true);
    $emptyTitle = $content['empty_title'] ?? 'No articles published yet';
    $emptyDescription = $content['empty_description'] ?? 'We are preparing expert guidance, project insights, and planning articles for Our Region homeowners.';
    $emptyButtonText = $content['empty_button_text'] ?? 'Back to Home';
    $emptyButtonUrl = $content['empty_button_url'] ?? '/';
    $currentPage = method_exists($postsSource, 'currentPage') ? (int) $postsSource->currentPage() : 1;
    $isPaginator = method_exists($postsSource, 'links');
    $featuredPost = $showFeaturedHero && !$isCategoryPage && $currentPage === 1 ? $postsCollection->first() : null;
    $gridPosts = $featuredPost ? $postsCollection->skip(1) : $postsCollection;
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'surface' => 'bg-[rgba(255,255,255,0.05)] border-white/12',
            'pill' => 'bg-white text-forest',
            'pillMuted' => 'bg-white/10 text-white/72 hover:bg-white/15',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'surface' => 'bg-white border-stone',
            'pill' => 'bg-forest text-white',
            'pillMuted' => 'bg-forest/6 text-text-secondary hover:bg-forest/10',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'surface' => 'bg-white border-stone',
            'pill' => 'bg-forest text-white',
            'pillMuted' => 'bg-forest/6 text-text-secondary hover:bg-forest/10',
        ],
    };
@endphp

<div class="space-y-10">
    <div class="max-w-5xl">
        @if($eyebrow)
            <p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>
        @endif
        @if($heading)
            <h1 class="mt-4 text-h2 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h1>
        @endif
        @if($subtitle)
            <p class="mt-4 text-body-lg {{ $toneMap['sub'] }} max-w-3xl">{{ $subtitle }}</p>
        @endif
        @if($isCategoryPage && !empty($currentCategory->description))
            <div class="mt-6 max-w-3xl prose prose-lg text-text-secondary">
                {!! nl2br(e($currentCategory->description)) !!}
            </div>
        @endif
    </div>

    @if($showCategoryTabs && $categories->count() > 1)
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('blog.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition {{ !$isCategoryPage ? $toneMap['pill'] : $toneMap['pillMuted'] }}">
                All Articles
            </a>
            @foreach($categories as $category)
                <a href="{{ route('blog.category', $category->slug) }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition {{ $isCategoryPage && $category->id === $currentCategory->id ? $toneMap['pill'] : $toneMap['pillMuted'] }}">
                    {{ $category->name }}
                    @if(isset($category->posts_count))
                        <span class="ml-1 opacity-70">({{ $category->posts_count }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    @endif

    @if($postsCollection->isNotEmpty())
        @if($featuredPost)
            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="group block {{ $toneMap['surface'] }} border overflow-hidden rounded-[1.75rem] shadow-editorial transition-all duration-500 hover:border-forest hover:shadow-luxury">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                    <div class="aspect-[4/3] md:aspect-auto overflow-hidden bg-forest/10">
                        @if($featuredPost->heroMedia)
                            <img src="{{ $featuredPost->heroMedia->url }}" alt="{{ $featuredPost->heroMedia->default_alt_text ?? $featuredPost->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" width="700" height="525" loading="eager" fetchpriority="high" decoding="async">
                        @else
                            <div class="w-full h-full min-h-64 flex items-center justify-center"><i data-lucide="file-text" class="w-12 h-12 text-stone"></i></div>
                        @endif
                    </div>
                    <div class="p-8 md:p-10 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-4">
                            @if($featuredPost->category)<span class="text-xs bg-forest/10 text-forest px-3 py-1.5 rounded-full font-semibold">{{ $featuredPost->category->name }}</span>@endif
                            @if($featuredPost->published_at)<span class="text-xs {{ $toneMap['sub'] }}">{{ $featuredPost->published_at->format('M j, Y') }}</span>@endif
                        </div>
                        <h2 class="text-2xl font-heading font-bold {{ $toneMap['heading'] }} group-hover:text-forest transition-colors leading-tight">{{ $featuredPost->title }}</h2>
                        @if($featuredPost->excerpt)<p class="mt-3 {{ $toneMap['sub'] }} leading-relaxed">{{ $featuredPost->excerpt }}</p>@endif
                        <div class="mt-6 flex items-center gap-2 text-forest font-semibold">
                            Read Article <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>
        @endif

        @if($gridPosts->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($gridPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group {{ $toneMap['surface'] }} border overflow-hidden rounded-[1.5rem] transition-all duration-500 hover:border-forest hover:shadow-luxury">
                        <div class="aspect-video overflow-hidden bg-forest/10">
                            @if($post->heroMedia)
                                <img src="{{ $post->heroMedia->url }}" alt="{{ $post->heroMedia->default_alt_text ?? $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" width="600" height="338" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i data-lucide="file-text" class="w-10 h-10 text-stone"></i></div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                @if($post->category)
                                    <span class="text-xs bg-forest/10 text-forest px-2.5 py-1 rounded-full font-medium">{{ $post->category->name }}</span>
                                @elseif($isCategoryPage)
                                    <span class="text-xs bg-forest/10 text-forest px-2.5 py-1 rounded-full font-medium">{{ $currentCategory->name }}</span>
                                @endif
                                @if($post->published_at)<span class="text-xs {{ $toneMap['sub'] }}">{{ $post->published_at->format('M j, Y') }}</span>@endif
                            </div>
                            <h2 class="text-base font-bold {{ $toneMap['heading'] }} group-hover:text-forest transition-colors line-clamp-2">{{ $post->title }}</h2>
                            @if($post->excerpt)<p class="mt-2 text-sm {{ $toneMap['sub'] }} line-clamp-3">{{ $post->excerpt }}</p>@endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($isPaginator)
            <div class="mt-10">{{ $postsSource->links() }}</div>
        @endif
    @else
        <div class="{{ $toneMap['surface'] }} border rounded-[1.75rem] p-10 text-center">
            <i data-lucide="file-search" class="w-14 h-14 text-stone mx-auto mb-4"></i>
            <h2 class="text-xl font-bold {{ $toneMap['heading'] }} mb-2">{{ $emptyTitle }}</h2>
            <p class="{{ $toneMap['sub'] }} mb-6 max-w-xl mx-auto">{{ $emptyDescription }}</p>
            <a href="{{ $emptyButtonUrl }}" class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-semibold px-5 py-3 rounded-full transition">
                {{ $emptyButtonText }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    @endif
</div>
