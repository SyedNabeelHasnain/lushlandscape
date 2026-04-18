{{-- Block: portfolio_directory --}}
@php
    $projectsSource = $context['projects'] ?? $data;
    $projectsCollection = method_exists($projectsSource, 'getCollection')
        ? $projectsSource->getCollection()
        : collect($projectsSource);
    $categories = collect($context['categories'] ?? []);
    $cities = collect($context['cities'] ?? []);
    $currentCategory = $context['category'] ?? null;
    $activeCategory = $context['activeCategory'] ?? ($currentCategory->slug ?? null);
    $activeCity = $context['activeCity'] ?? null;
    $featured = (bool) ($context['featured'] ?? false);
    $isCategoryPage = $currentCategory !== null;
    $currentPage = method_exists($projectsSource, 'currentPage') ? (int) $projectsSource->currentPage() : 1;
    $isPaginator = method_exists($projectsSource, 'links');
    $showFilters = (bool) ($content['show_filters'] ?? true);
    $showFeaturedHero = (bool) ($content['show_featured_hero'] ?? true);
    $showCategoryPills = (bool) ($content['show_category_pills'] ?? true);
    $heading = $content['heading'] ?? ($isCategoryPage ? $currentCategory->name : 'Our Project Portfolio');
    $subtitle = $content['subtitle'] ?? ($isCategoryPage
        ? ($currentCategory->short_description ?? '')
        : 'Real projects, real results. Browse our completed professional work across Our Region.');
    $eyebrow = $content['eyebrow'] ?? ($isCategoryPage ? 'Portfolio Category' : '');
    $tone = $content['tone'] ?? 'light';
    $emptyTitle = $content['empty_title'] ?? 'No projects found';
    $emptyDescription = $content['empty_description'] ?? 'Try adjusting your filters or browse all projects.';
    $emptyButtonText = $content['empty_button_text'] ?? 'View All Projects';
    $emptyButtonUrl = $content['empty_button_url'] ?? '/portfolio';
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'surface' => 'bg-[rgba(255,255,255,0.05)] border-white/12',
            'pill' => 'bg-white/10 text-white',
            'pillMuted' => 'bg-white/5 text-white/72',
            'button' => 'bg-white text-forest hover:bg-white/90',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'surface' => 'bg-white border-stone',
            'pill' => 'bg-forest text-white',
            'pillMuted' => 'bg-forest/6 text-text-secondary hover:bg-forest/10',
            'button' => 'bg-forest text-white hover:bg-forest-light',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'surface' => 'bg-white border-stone',
            'pill' => 'bg-forest text-white',
            'pillMuted' => 'bg-forest/6 text-text-secondary hover:bg-forest/10',
            'button' => 'bg-forest text-white hover:bg-forest-light',
        ],
    };
    $featuredHeroAllowed = $showFeaturedHero && !$isCategoryPage && !$activeCategory && !$activeCity && !$featured && $currentPage === 1;
    $featuredProject = $featuredHeroAllowed ? $projectsCollection->first() : null;
    $gridProjects = $featuredHeroAllowed ? $projectsCollection->skip(1) : $projectsCollection;
    $buildPortfolioUrl = function (array $params = []): string {
        $query = http_build_query(array_filter($params, fn ($value) => $value !== null && $value !== '' && $value !== false));
        return url('/portfolio'.($query ? '?'.$query : ''));
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

        @if(!$isCategoryPage && ($activeCategory || $activeCity || $featured))
            <div class="mt-5 flex items-center gap-2 flex-wrap">
                <span class="text-sm {{ $toneMap['sub'] }}">Filtered by:</span>
                @if($activeCategory)
                    <span class="inline-flex items-center gap-1 {{ $toneMap['pill'] }} text-sm px-3 py-1 font-medium rounded-full">
                        {{ $categories->firstWhere('slug', $activeCategory)?->name ?? $activeCategory }}
                    </span>
                @endif
                @if($activeCity)
                    <span class="inline-flex items-center gap-1 {{ $toneMap['pill'] }} text-sm px-3 py-1 font-medium rounded-full">
                        {{ $activeCity }}
                    </span>
                @endif
                @if($featured)
                    <span class="inline-flex items-center gap-1 {{ $toneMap['pillMuted'] }} text-sm px-3 py-1 font-medium rounded-full">
                        Featured Only
                    </span>
                @endif
                <a href="{{ $buildPortfolioUrl() }}" class="text-sm text-forest hover:text-black font-medium underline underline-offset-2">Clear All</a>
            </div>
        @endif
    </div>

    @if($isCategoryPage && $showCategoryPills && $categories->count() > 1)
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('portfolio.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition {{ $toneMap['pillMuted'] }}">
                All Projects
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('portfolio.category', $cat->slug) }}" class="px-3 py-1.5 text-sm font-medium rounded-full transition {{ $cat->id === $currentCategory->id ? $toneMap['pill'] : $toneMap['pillMuted'] }}">
                    {{ $cat->name }}
                    @if(isset($cat->projects_count))
                        <span class="ml-1 opacity-70">({{ $cat->projects_count }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    @elseif(!$isCategoryPage && $showFilters)
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex flex-wrap gap-2">
                <a href="{{ $buildPortfolioUrl(['city' => $activeCity, 'featured' => $featured ? '1' : null]) }}"
                    class="px-4 py-2 text-eyebrow font-medium rounded-full transition {{ !$activeCategory ? $toneMap['pill'] : $toneMap['pillMuted'] }}">
                    All Projects
                </a>
                @foreach($categories as $cat)
                    <a href="{{ $buildPortfolioUrl(['category' => $cat->slug, 'city' => $activeCity, 'featured' => $featured ? '1' : null]) }}"
                        class="px-4 py-2 text-eyebrow font-medium rounded-full transition {{ $activeCategory === $cat->slug ? $toneMap['pill'] : $toneMap['pillMuted'] }}">
                        {{ $cat->name }}
                        @if(isset($cat->projects_count))
                            <span class="ml-1 opacity-70">({{ $cat->projects_count }})</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @if($cities->isNotEmpty())
                    <form method="GET" action="{{ url('/portfolio') }}" class="flex items-center gap-3">
                        @if($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory }}">@endif
                        @if($featured)<input type="hidden" name="featured" value="1">@endif
                        <label for="portfolio-city-filter" class="sr-only">Filter portfolio by city</label>
                        <select id="portfolio-city-filter" name="city" onchange="this.form.submit()" aria-label="Filter portfolio by city"
                            class="min-w-[12rem] rounded-full border border-stone bg-white px-4 py-2 text-sm text-ink focus:border-forest focus:outline-none focus:ring-1 focus:ring-forest">
                            <option value="">All Cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->name }}" @selected($activeCity === $city->name)>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <a href="{{ $buildPortfolioUrl(['category' => $activeCategory, 'city' => $activeCity, 'featured' => $featured ? null : '1']) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium transition {{ $featured ? $toneMap['pill'] : $toneMap['pillMuted'] }}">
                    <i data-lucide="star" class="w-3.5 h-3.5"></i>
                    Featured
                </a>
            </div>
        </div>
    @endif

    @if($projectsCollection->isNotEmpty())
        @if($featuredProject)
            <a href="{{ route('portfolio.show', $featuredProject->slug) }}" class="group block {{ $toneMap['surface'] }} border overflow-hidden rounded-[1.75rem] shadow-editorial transition-all duration-500 hover:border-forest hover:shadow-luxury">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                    <div class="aspect-[4/3] md:aspect-auto md:min-h-80 overflow-hidden bg-forest/10 relative">
                        @if($featuredProject->heroMedia)
                            <x-frontend.media :asset="$featuredProject->heroMedia" :alt="$featuredProject->title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" fetchpriority="high" loading="eager" />
                        @else
                            <div class="w-full h-full min-h-64 flex items-center justify-center"><i data-lucide="image" class="w-12 h-12 text-stone"></i></div>
                        @endif
                        @if($featuredProject->is_featured)
                            <div class="absolute top-3 right-3"><span class="bg-white text-forest text-xs px-2.5 py-1 rounded-full font-semibold">Featured</span></div>
                        @endif
                    </div>
                    <div class="p-8 md:p-10 flex flex-col justify-center">
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            @if($featuredProject->city)<span class="text-xs bg-forest/10 text-forest px-3 py-1.5 rounded-full font-semibold">{{ $featuredProject->city->name }}</span>@endif
                            @if($featuredProject->service)<span class="text-xs bg-forest/6 text-text-secondary px-3 py-1.5 rounded-full">{{ $featuredProject->service->name }}</span>@endif
                            @if($featuredProject->completion_date)<span class="text-xs text-text-secondary">Completed {{ $featuredProject->completion_date->format('M Y') }}</span>@endif
                        </div>
                        <h2 class="text-2xl font-heading font-bold {{ $toneMap['heading'] }} group-hover:text-forest transition-colors leading-tight">{{ $featuredProject->title }}</h2>
                        @if($featuredProject->description)<p class="mt-3 {{ $toneMap['sub'] }} leading-relaxed">{{ $featuredProject->description }}</p>@endif
                        @if($featuredProject->project_value_range)
                            <div class="mt-4 text-sm {{ $toneMap['sub'] }}"><span class="font-medium {{ $toneMap['heading'] }}">Project Value:</span> {{ $featuredProject->project_value_range }}</div>
                        @endif
                        <div class="mt-6 flex items-center gap-2 text-forest font-semibold">
                            View Project <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($gridProjects as $project)
                <a href="{{ route('portfolio.show', $project->slug) }}" class="group {{ $toneMap['surface'] }} border overflow-hidden rounded-[1.5rem] transition-all duration-500 hover:border-forest hover:shadow-luxury">
                    <div class="aspect-[4/3] overflow-hidden bg-forest/10 relative">
                        @if($project->heroMedia)
                            <x-frontend.media :asset="$project->heroMedia" :alt="$project->title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i data-lucide="image" class="w-12 h-12 text-stone"></i></div>
                        @endif
                        @if($project->is_featured)
                            <div class="absolute top-3 right-3"><span class="bg-white text-forest text-xs px-2.5 py-1 rounded-full font-semibold">Featured</span></div>
                        @endif
                    </div>
                    <div class="p-6">
                        <h2 class="text-base font-bold {{ $toneMap['heading'] }} group-hover:text-forest transition-colors">{{ $project->title }}</h2>
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @if($isCategoryPage)
                                <span class="text-xs bg-forest/10 text-forest px-2.5 py-1 rounded-full font-medium">{{ $currentCategory->name }}</span>
                            @elseif($project->category)
                                <span class="text-xs bg-forest/10 text-forest px-2.5 py-1 rounded-full font-medium">{{ $project->category->name }}</span>
                            @endif
                            @if($project->city)<span class="text-xs bg-forest/6 text-text-secondary px-2.5 py-1 rounded-full">{{ $project->city->name }}</span>@endif
                            @if($project->service)<span class="text-xs bg-forest/6 text-text-secondary px-2.5 py-1 rounded-full">{{ $project->service->name }}</span>@endif
                            @if($project->project_value_range)<span class="text-xs bg-forest/6 text-text-secondary px-2.5 py-1 rounded-full">{{ $project->project_value_range }}</span>@endif
                        </div>
                        @if($project->description)<p class="mt-3 text-sm {{ $toneMap['sub'] }} line-clamp-2">{{ $project->description }}</p>@endif
                        <div class="mt-4 flex items-center gap-1.5 text-forest text-sm font-semibold">
                            View Project <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($isPaginator)
            <div class="mt-10">{{ $projectsSource->links() }}</div>
        @endif
    @else
        <div class="{{ $toneMap['surface'] }} border rounded-[1.75rem] p-10 text-center">
            <i data-lucide="search-x" class="w-16 h-16 text-stone mx-auto mb-4"></i>
            <h2 class="text-xl font-bold {{ $toneMap['heading'] }} mb-2">{{ $emptyTitle }}</h2>
            <p class="{{ $toneMap['sub'] }} mb-6 max-w-xl mx-auto">{{ $emptyDescription }}</p>
            <a href="{{ $emptyButtonUrl }}" class="inline-flex items-center gap-2 {{ $toneMap['button'] }} font-semibold px-6 py-3 rounded-full transition">
                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                {{ $emptyButtonText }}
            </a>
        </div>
    @endif
</div>
