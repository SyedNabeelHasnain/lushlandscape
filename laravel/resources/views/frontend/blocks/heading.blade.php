@php
    $tag   = in_array($content['level'] ?? 'h2', ['h1','h2','h3','h4','h5','h6']) ? ($content['level'] ?? 'h2') : 'h2';
    $align = match($content['align'] ?? 'left') { 'center' => 'text-center', 'right' => 'text-right', default => 'text-left' };
    $size  = match($tag) { 'h1' => 'text-display', 'h2' => 'text-h2', 'h3' => 'text-h3', default => 'text-xl md:text-2xl' };
    $color = match($tag) { 'h1', 'h2' => 'text-forest', default => 'text-ink' };
@endphp
@if(!empty($content['text']))
<div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 sm:py-4 lg:px-12">
    <{{ $tag }} class="{{ $size }} font-heading font-bold {{ $color }} {{ $align }}">{{ $content['text'] }}</{{ $tag }}>
</div>
@endif
