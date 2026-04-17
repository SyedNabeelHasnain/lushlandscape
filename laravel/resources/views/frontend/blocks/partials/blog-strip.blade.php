{{-- Block: blog_strip --}}
@if($data->isNotEmpty())
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = !empty($content['heading']) ? $content['heading'] : 'Latest from Our Blog';
    $subtitle = $content['subtitle'] ?? '';
    $layout = $content['layout'] ?? 'grid';
    $variant = $content['variant'] ?? 'editorial';
    $tone = $content['tone'] ?? 'light';
    $showViewAll = $content['show_view_all'] ?? true;
    $viewAllText = $content['view_all_text'] ?? 'View All Posts';
    $viewAllUrl = $content['view_all_url'] ?? '/blog';
    $gridColsClass = match (min($data->count(), 3)) {
        1 => 'md:grid-cols-1',
        2 => 'md:grid-cols-2',
        default => 'md:grid-cols-3',
    };
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'meta' => 'text-white/55',
            'link' => 'text-white',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'meta' => 'text-text-secondary',
            'link' => 'text-forest',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'meta' => 'text-text-secondary',
            'link' => 'text-forest',
        ],
    };
@endphp

<div class="mb-10 max-w-4xl">
    @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
    @if($heading)<h2 class="mt-4 text-h2 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h2>@endif
    @if($subtitle)<p class="mt-4 text-body-lg {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
</div>

<div class="grid grid-cols-1 {{ $gridColsClass }} gap-8">
    @foreach($data as $post)
    @php
        $cardShell = $variant === 'minimal' ? '' : 'editorial-card rounded-[1.75rem]';
    @endphp
    <a href="{{ url('/blog/' .  $post->slug  . '') }}" class="group block {{ $cardShell }}">
        @if($post->heroMedia)
        <div class="relative overflow-hidden rounded-[1.5rem] aspect-[16/9] {{ $variant === 'minimal' ? 'mb-4' : 'mb-6' }}">
            <x-frontend.media :asset="$post->heroMedia" :alt="$post->title" class="img-zoom w-full h-full object-cover" />
        </div>
        @endif
        @if($post->category)
        <span class="text-[11px] font-semibold uppercase tracking-[0.22em] {{ $toneMap['link'] }}">{{ $post->category->name }}</span>
        @endif
        <h3 class="mt-2 text-h3 font-heading font-bold leading-tight {{ $toneMap['heading'] }} {{ $tone === 'dark' ? 'group-hover:text-white' : 'group-hover:text-forest' }} transition">{{ $post->title }}</h3>
        @if($post->excerpt)<p class="mt-3 line-clamp-2 text-sm {{ $toneMap['sub'] }}">{{ $post->excerpt }}</p>@endif
        @if($post->published_at)<p class="mt-4 text-[11px] uppercase tracking-[0.2em] {{ $toneMap['meta'] }}">{{ $post->published_at->format('M d, Y') }}</p>@endif
    </a>
    @endforeach
</div>

@if($showViewAll && $viewAllText && $viewAllUrl)
<div class="mt-10">
    <a href="{{ $viewAllUrl }}" class="inline-flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.18em] {{ $toneMap['link'] }}">
        {{ $viewAllText }} <span class="w-8 h-px bg-current/35"></span>
    </a>
</div>
@endif
@endif
