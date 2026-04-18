@props(['author' => null, 'publishedAt' => null, 'updatedAt' => null])
@php
    $name    = is_object($author) ? ($author->name ?? 'Super WMS Team') : ($author ?? 'Super WMS Team');
    $initial = strtoupper(substr($name, 0, 1));
    $bio     = is_object($author) ? ($author->bio ?? null) : null;
    $avatar  = is_object($author) ? ($author->avatar_url ?? null) : null;
@endphp

<div class="flex items-start gap-6 p-10 bg-cream border border-stone mt-16">
    @if($avatar)
    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-14 h-14 object-cover shrink-0" width="56" height="56" loading="lazy">
    @else
    <div class="w-14 h-14 bg-forest flex items-center justify-center shrink-0">
        <span class="text-white text-xl font-bold">{{ $initial }}</span>
    </div>
    @endif
    <div class="min-w-0">
        <p class="text-eyebrow text-text-secondary mb-1">Written by</p>
        <p class="text-base font-heading font-bold text-ink">{{ $name }}</p>
        @if($bio)<p class="text-sm text-text-secondary mt-1.5 leading-relaxed">{{ $bio }}</p>@endif
        <div class="flex flex-wrap gap-4 mt-2 text-xs text-text-secondary">
            @if($publishedAt)<span>Published: <time datetime="{{ $publishedAt->toIso8601String() }}">{{ $publishedAt->format('F j, Y') }}</time></span>@endif
            @if($updatedAt && $updatedAt != $publishedAt)<span>Updated: <time datetime="{{ $updatedAt->toIso8601String() }}">{{ $updatedAt->format('F j, Y') }}</time></span>@endif
        </div>
    </div>
</div>
