@php
    $href = "/portfolio/{$project->slug}";
@endphp

<a href="{{ $href }}" class="group block {{ $cardClass }}">
    <div class="relative overflow-hidden {{ $imageRatio }} {{ $imageClass }} {{ $tone === 'dark' ? 'bg-white/8' : 'bg-stone-light' }}">
        @if($project->heroMedia)
            <x-frontend.media :asset="$project->heroMedia" :alt="$project->title" class="img-zoom w-full h-full object-cover" />
        @else
            <div class="w-full h-full bg-stone-light flex items-center justify-center">
                <i data-lucide="image" class="w-8 h-8 text-stone"></i>
            </div>
        @endif
    </div>
    <div class="{{ $padding }}">
        <h3 class="{{ $titleClass }} font-heading font-bold leading-tight {{ $toneMap['heading'] }} {{ $tone === 'dark' ? 'group-hover:text-white' : 'group-hover:text-forest' }} transition line-clamp-1">{{ $project->title }}</h3>
        @if($project->city || $project->service)
            <p class="mt-3 {{ $metaCase }} {{ $toneMap['meta'] }} line-clamp-1">
                {{ $project->city?->name ?? 'Ontario' }}
                @if($project->service)
                    <span class="mx-2 opacity-40">/</span>{{ $project->service->name }}
                @endif
            </p>
        @endif
        @if($project->description)
            <p class="mt-3 {{ $descClamp }} text-sm {{ $toneMap['sub'] }}">{{ $project->description }}</p>
        @endif
    </div>
</a>

