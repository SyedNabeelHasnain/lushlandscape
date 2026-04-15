@props(['review', 'compact' => false])
@php
    $sourceLabels = ['google'=>'Google','homestars'=>'HomeStars','houzz'=>'Houzz','direct'=>'Verified','yelp'=>'Yelp','bbb'=>'BBB'];
@endphp

<article class="bg-white border border-stone p-10 flex flex-col h-full hover:border-forest transition-all duration-500 hover:shadow-luxury">
    <div class="flex items-center justify-between gap-2 mb-6">
        <div class="flex items-center gap-0.5">
            @for($i = 1; $i <= 5; $i++)
            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-stone' }} fill-current" viewBox="0 0 20 20" aria-hidden="true">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            @endfor
            <span class="sr-only">{{ $review->rating }} out of 5 stars</span>
        </div>
        @if($review->source && isset($sourceLabels[$review->source]))
        <span class="text-eyebrow text-accent">{{ $sourceLabels[$review->source] }}</span>
        @endif
    </div>

    <blockquote class="text-ink leading-relaxed flex-1 font-heading text-lg italic {{ $compact ? 'line-clamp-4' : '' }}">
        <p>"{{ $review->content }}"</p>
    </blockquote>

    @if($review->project_type)
    <div class="mt-3">
        <span class="text-eyebrow text-text-secondary">{{ $review->project_type }}</span>
    </div>
    @endif

    <div class="flex items-center gap-4 mt-8 pt-8 border-t border-stone">
        @if($review->reviewer_avatar_url)
        <img src="{{ $review->reviewer_avatar_url }}"
             alt="{{ $review->reviewer_name }}"
             class="w-9 h-9 object-cover shrink-0"
             width="36" height="36" loading="lazy">
        @else
        <div class="w-9 h-9 bg-forest flex items-center justify-center shrink-0" aria-hidden="true">
            <span class="text-white text-xs font-bold">{{ $review->reviewer_initial ?? strtoupper(substr($review->reviewer_name, 0, 1)) }}</span>
        </div>
        @endif
        <div class="min-w-0">
            <p class="text-sm font-semibold text-ink truncate">{{ $review->reviewer_name }}</p>
            <p class="text-xs text-text-secondary truncate">
                {{ $review->city_relevance ?? '' }}
                @if($review->review_date) · <time datetime="{{ $review->review_date->toDateString() }}">{{ $review->review_date->format('M Y') }}</time> @endif
            </p>
        </div>
    </div>
</article>
