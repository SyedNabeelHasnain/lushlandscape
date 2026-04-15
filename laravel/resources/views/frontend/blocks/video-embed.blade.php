@php
    $url = $content['url'] ?? '';
    if (str_contains($url, 'youtube.com/watch')) {
        parse_str(parse_url($url, PHP_URL_QUERY), $qs);
        $url = 'https://www.youtube.com/embed/' . ($qs['v'] ?? '');
    } elseif (str_contains($url, 'youtu.be/')) {
        $url = 'https://www.youtube.com/embed/' . basename(parse_url($url, PHP_URL_PATH));
    } elseif (str_contains($url, 'vimeo.com/')) {
        $url = 'https://player.vimeo.com/video/' . basename(parse_url($url, PHP_URL_PATH));
    }
    $aspect = match($content['aspect'] ?? '16:9') { '4:3' => 'aspect-4/3', '1:1' => 'aspect-square', default => 'aspect-video' };
@endphp
@if(!empty($url))
<div class="max-w-4xl mx-auto px-6 lg:px-12 py-8 reveal">
    <div class="{{ $aspect }} w-full overflow-hidden bg-ink border border-stone shadow-luxury">
        <iframe src="{{ $url }}" class="w-full h-full" frameborder="0" allowfullscreen
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                loading="lazy"></iframe>
    </div>
    @if(!empty($content['caption']))
    <p class="text-xs text-text-secondary text-center mt-3">{{ $content['caption'] }}</p>
    @endif
</div>
@endif
