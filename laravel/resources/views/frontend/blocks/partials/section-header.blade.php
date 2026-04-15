{{-- Block: section_header --}}
@php
    $heading = $content['heading'] ?? '';
    $subtitle = $content['subtitle'] ?? '';
    $align = $content['align'] ?? 'center';
    $tag = $content['tag'] ?? '';
    $showLine = $content['show_line'] ?? true;
    $alignClass = $align === 'left' ? 'text-left' : 'text-center';
@endphp
<div class="{{ $alignClass }}">
    @if($tag)<span class="text-forest font-semibold text-sm uppercase tracking-wider">{{ $tag }}</span>@endif
    @if($heading)<h2 class="text-3xl font-bold text-text mt-2">{{ $heading }}</h2>@endif
    @if($subtitle)<p class="mt-3 text-text-secondary text-lg max-w-2xl mx-auto">{{ $subtitle }}</p>@endif
    @if($showLine)<div class="mt-4 w-16 h-1 bg-forest {{ $align === 'center' ? 'mx-auto' : '' }}"></div>@endif
</div>
