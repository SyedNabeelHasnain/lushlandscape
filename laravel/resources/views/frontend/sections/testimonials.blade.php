{{-- Section: testimonials --}}
@php
    use App\Models\Review;
    $testLimit   = (int) ($section['settings']['limit'] ?? 3);

    // Resolve city context: city page passes $city directly, service-city page passes $page with $page->city
    $isCityContext = isset($city);
    $isPageContext = isset($page);
    $cityObj       = $isCityContext ? $city : ($isPageContext ? $page->city : null);
    $cityName      = $cityObj?->name;

    $testHeading = !empty($section['settings']['heading'])
        ? $section['settings']['heading']
        : ($cityName
            ? 'What ' . $cityName . ' Clients Say'
            : (isset($service) ? 'What Our ' . $service->name . ' Clients Say' : 'What Our Clients Say'));

    $reviewQuery = Review::where('status', 'published');

    if ($cityName) {
        $reviewQuery->where(function ($q) use ($cityName) {
            $q->where('city_relevance', $cityName)->orWhere('is_featured', true);
        })->orderByRaw('(city_relevance = ?) DESC', [$cityName]);
    } else {
        $reviewQuery->where('is_featured', true);
    }

    $reviews = $reviewQuery->orderBy('sort_order')->take($testLimit)->get();
@endphp
@if($reviews->isNotEmpty())
<section class="section-editorial bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-text">{{ $testHeading }}</h2>
            @if($cityName)
            <p class="mt-3 text-text-secondary text-lg">Trusted by homeowners across {{ $cityName }} and the surrounding area.</p>
            @else
            <p class="mt-3 text-text-secondary text-lg">Trusted by homeowners across Ontario.</p>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-{{ min($reviews->count(), 3) }} gap-6">
            @foreach($reviews as $review)
            <div class="bg-cream  border border-stone p-7 flex flex-col"
                >
                {{-- Stars --}}
                <div class="flex gap-0.5 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                    <i data-lucide="star" class="w-4 h-4 {{ $i <= ($review->rating ?? 5) ? 'text-amber-400 fill-amber-400' : 'text-stone' }}"></i>
                    @endfor
                </div>
                <p class="text-sm text-text-secondary leading-relaxed flex-1">&ldquo;{{ $review->content }}&rdquo;</p>
                <div class="mt-5 flex items-center gap-3">
                    <div class="w-10 h-10  bg-forest text-white flex items-center justify-center shrink-0 font-bold text-sm">
                        {{ $review->reviewer_initial ?? mb_strtoupper(mb_substr($review->reviewer_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-text">{{ $review->reviewer_name }}</p>
                        @if($review->city_relevance)
                        <p class="text-xs text-text-secondary">{{ $review->city_relevance }}</p>
                        @endif
                    </div>
                    @if($review->source)
                    <div class="ml-auto">
                        <span class="text-xs text-text-secondary bg-white border border-stone px-2 py-1 ">{{ ucfirst($review->source) }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
