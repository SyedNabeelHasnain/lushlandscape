{{-- Block: blockquote --}}
@php
    $text = $content['text'] ?? '';
    $author = $content['author'] ?? '';
    $style = $content['style'] ?? 'bordered';
@endphp
@if($text)
@if($style === 'card')
<div class="bg-cream border border-stone p-8 rounded-xl">
    <blockquote class="text-lg text-text-secondary italic leading-relaxed">&ldquo;{{ $text }}&rdquo;</blockquote>
    @if($author)<cite class="block mt-4 text-sm font-semibold text-text not-italic">— {{ $author }}</cite>@endif
</div>
@elseif($style === 'large')
<blockquote class="text-center">
    <p class="text-2xl text-text-secondary italic leading-relaxed max-w-3xl mx-auto">&ldquo;{{ $text }}&rdquo;</p>
    @if($author)<cite class="block mt-4 text-sm font-semibold text-text not-italic">— {{ $author }}</cite>@endif
</blockquote>
@else
<blockquote class="border-l-4 border-forest pl-6">
    <p class="text-lg text-text-secondary italic leading-relaxed">&ldquo;{{ $text }}&rdquo;</p>
    @if($author)<cite class="block mt-2 text-sm font-semibold text-text not-italic">— {{ $author }}</cite>@endif
</blockquote>
@endif
@endif
