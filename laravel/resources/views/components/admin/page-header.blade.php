@props(['title', 'subtitle' => null, 'createRoute' => null, 'createLabel' => 'Create New', 'viewUrl' => null])
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-text">{{ $title }}</h1>
        @if($subtitle)<p class="text-text-secondary mt-1">{{ $subtitle }}</p>@endif
    </div>
    <div class="flex w-full flex-wrap items-stretch gap-3 sm:w-auto sm:items-center sm:justify-end">
        @if($viewUrl)
        <a href="{{ $viewUrl }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-forest/15 px-4 py-2.5 text-sm font-medium text-forest hover:bg-forest-50 hover:text-forest-light transition sm:w-auto">
            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>View Page
        </a>
        @endif
        @if($createRoute)
        <a href="{{ $createRoute }}" class="inline-flex w-full items-center justify-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-4 py-2.5 rounded-xl transition text-sm sm:w-auto">
            <i data-lucide="plus" class="w-4 h-4"></i>{{ $createLabel }}
        </a>
        @endif
        {{ $slot ?? '' }}
    </div>
</div>
