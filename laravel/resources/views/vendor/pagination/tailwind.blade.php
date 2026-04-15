@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="flex items-center justify-center gap-1">
    {{-- Previous --}}
    @if ($paginator->onFirstPage())
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-300 cursor-not-allowed" aria-disabled="true" aria-label="Previous page">
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-text hover:bg-forest hover:text-white transition" aria-label="Previous page">
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
    </a>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
        <span class="inline-flex items-center justify-center w-10 h-10 text-sm text-text-secondary" aria-hidden="true">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-forest text-white text-sm font-semibold" aria-current="page">{{ $page }}</span>
                @else
                <a href="{{ $url }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-sm text-text hover:bg-forest hover:text-white transition" aria-label="Page {{ $page }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-text hover:bg-forest hover:text-white transition" aria-label="Next page">
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
    </a>
    @else
    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-gray-300 cursor-not-allowed" aria-disabled="true" aria-label="Next page">
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
    </span>
    @endif
</nav>
@endif
