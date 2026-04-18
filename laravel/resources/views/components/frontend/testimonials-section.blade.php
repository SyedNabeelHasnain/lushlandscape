@props([
    'reviews'  => collect(),
    'title'    => 'What Our Region Homeowners Say',
    'subtitle' => '',
])
@php
    $googleRating = \App\Models\Setting::get('google_rating', '');
    $reviewCount  = \App\Models\Setting::get('google_review_count', '');
    $googleBizUrl = \App\Models\Setting::get('google_business_url', '#');
    $sourceLabels = ['google'=>'Google','homestars'=>'HomeStars','houzz'=>'Houzz','direct'=>'Verified','yelp'=>'Yelp','bbb'=>'BBB'];
@endphp

@if($reviews->count() > 0)
<section class="section-editorial bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-24 mb-20">
            <div class="lg:col-span-7 reveal">
                <span class="text-eyebrow text-forest mb-5 block">Testimonials</span>
                <h2 class="text-h2 font-heading font-bold text-ink">{{ $title }}</h2>
                @if($subtitle)<p class="mt-5 text-text-secondary text-body-lg">{{ $subtitle }}</p>@endif
            </div>
            @if($googleRating && $reviewCount)
            <div class="lg:col-span-5 flex items-end reveal">
                <div class="bg-white border border-stone p-10 w-full">
                    <div class="flex items-center gap-1 mb-3">
                        @for($i=0;$i<5;$i++)<svg class="w-5 h-5 text-accent fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                    </div>
                    <div class="text-5xl font-heading font-bold text-ink">{{ $googleRating }}</div>
                    <div class="text-eyebrow text-text-secondary">{{ $reviewCount }} Google Reviews</div>
                    @if($googleBizUrl && $googleBizUrl !== '#')
                    <a href="{{ $googleBizUrl }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-5 text-sm text-forest font-semibold link-underline">Read all reviews &rarr;</a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="swiper testimonials-swiper">
            <div class="swiper-wrapper pb-4">
                @foreach($reviews as $review)
                <div class="swiper-slide h-auto">
                    <div class="bg-white border border-stone p-10 h-full flex flex-col hover:border-forest transition-all duration-500">
                        <div class="flex items-center gap-1 mb-6">
                            @for($i=0;$i<$review->rating;$i++)<svg class="w-4 h-4 text-accent fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor
                            @if($review->source && isset($sourceLabels[$review->source]))
                            <span class="ml-auto text-eyebrow text-accent">{{ $sourceLabels[$review->source] }}</span>
                            @endif
                        </div>
                        <blockquote class="text-sm text-ink leading-relaxed flex-1 font-heading text-lg italic">
                            <p>"{{ $review->content }}"</p>
                        </blockquote>
                        <div class="flex items-center gap-4 mt-8 pt-8 border-t border-stone">
                            @if($review->reviewer_avatar_url)
                            <img src="{{ $review->reviewer_avatar_url }}" alt="{{ $review->reviewer_name }}" class="w-11 h-11 object-cover" width="44" height="44" loading="lazy">
                            @else
                            <div class="w-11 h-11 bg-forest flex items-center justify-center shrink-0">
                                <span class="text-white text-sm font-semibold">{{ $review->reviewer_initial ?? substr($review->reviewer_name,0,1) }}</span>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-ink truncate">{{ $review->reviewer_name }}</p>
                                <p class="text-xs text-text-secondary mt-0.5">
                                    {{ $review->city_relevance ?? '' }}
                                    @if($review->project_type) &middot; {{ $review->project_type }} @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="swiper-pagination testimonials-pagination mt-12"></div>
        </div>
    </div>
</section>
@endif
