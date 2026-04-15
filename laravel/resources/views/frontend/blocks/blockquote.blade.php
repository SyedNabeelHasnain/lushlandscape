@php
    $style = $content['style'] ?? 'bordered';
@endphp
@if(!empty($content['text']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
    @if($style === 'large')
    <blockquote class="text-center max-w-3xl mx-auto">
        <svg class="w-10 h-10 text-forest/30 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11.3 2.5c-2 1-3.6 2.3-4.8 4-1.2 1.6-1.8 3.4-1.8 5.3 0 1.3.4 2.3 1.2 3.1.8.8 1.8 1.1 3 1.1 1.1 0 2-.4 2.7-1.1.7-.7 1.1-1.6 1.1-2.7 0-1-.3-1.8-1-2.5-.6-.7-1.4-1-2.4-1-.4 0-.7.1-1 .2.5-1.6 1.5-3 2.8-4.4l-1.8-2zm9.7 0c-2 1-3.6 2.3-4.8 4-1.2 1.6-1.8 3.4-1.8 5.3 0 1.3.4 2.3 1.2 3.1.8.8 1.8 1.1 3 1.1 1.1 0 2-.4 2.7-1.1.7-.7 1.1-1.6 1.1-2.7 0-1-.3-1.8-1-2.5-.6-.7-1.4-1-2.4-1-.4 0-.7.1-1 .2.5-1.6 1.5-3 2.8-4.4l-1.8-2z"/></svg>
        <p class="text-xl md:text-2xl font-medium text-text italic leading-relaxed">{{ $content['text'] }}</p>
        @if(!empty($content['author']))
        <cite class="block mt-4 text-sm font-semibold text-text-secondary not-italic">- {{ $content['author'] }}</cite>
        @endif
    </blockquote>
    @elseif($style === 'card')
    <blockquote class="bg-cream  p-6 md:p-8 border border-stone">
        <p class="text-base md:text-lg text-text leading-relaxed italic">{{ $content['text'] }}</p>
        @if(!empty($content['author']))
        <cite class="block mt-4 text-sm font-semibold text-forest not-italic">- {{ $content['author'] }}</cite>
        @endif
    </blockquote>
    @else
    <blockquote class="border-l-4 border-forest pl-5 py-2">
        <p class="text-base md:text-lg text-text leading-relaxed italic">{{ $content['text'] }}</p>
        @if(!empty($content['author']))
        <cite class="block mt-3 text-sm font-semibold text-text-secondary not-italic">- {{ $content['author'] }}</cite>
        @endif
    </blockquote>
    @endif
</div>
@endif
