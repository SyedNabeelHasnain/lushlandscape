@props(['posts' => collect(), 'title' => 'Related Articles'])

@if($posts->count() > 0)
<section class="section-editorial bg-cream">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-h3 font-heading font-bold text-ink mb-10">{{ $title }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group bg-white border border-stone overflow-hidden hover:border-forest transition-all duration-500">
                @if($post->heroMedia ?? null)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ $post->heroMedia->url }}" alt="{{ $post->heroMedia->default_alt_text ?? $post->title }}" class="w-full h-full object-cover img-zoom" width="400" height="225" loading="lazy">
                </div>
                @endif
                <div class="p-5">
                    <p class="text-xs text-text-secondary mb-2">{{ $post->published_at?->format('M j, Y') }}</p>
                    <h3 class="text-sm font-heading font-bold text-ink group-hover:text-forest transition-colors line-clamp-2">{{ $post->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
