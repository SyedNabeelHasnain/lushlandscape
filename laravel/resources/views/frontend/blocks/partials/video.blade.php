{{-- Block: video --}}
@php
    $url = $content['url'] ?? '';
    $mediaId = $content['media_id'] ?? null;
    $autoplay = $content['autoplay'] ?? false;
    $muted = $content['muted'] ?? true;
    $loop = $content['loop'] ?? false;
    $aspectRatio = $content['aspect_ratio'] ?? '16:9';
    $aspectMap = ['16:9' => 'aspect-video', '4:3' => 'aspect-[4/3]', '1:1' => 'aspect-square'];
    $aspectClass = $aspectMap[$aspectRatio] ?? 'aspect-video';
    $embed = $url ? app(\App\Services\VideoEmbedService::class)->resolve($url, [
        'autoplay' => $autoplay,
        'muted' => $muted,
        'loop' => $loop,
    ]) : null;
@endphp
<div class="{{ $aspectClass }} rounded-xl overflow-hidden">
    @if($url)
        @if(($embed['type'] ?? null) === 'iframe')
        <iframe src="{{ $embed['src'] }}" class="w-full h-full" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
        @elseif(($embed['type'] ?? null) === 'video')
        <video src="{{ $embed['src'] }}" class="w-full h-full" {{ $autoplay ? 'autoplay' : '' }} {{ $muted ? 'muted' : '' }} {{ $loop ? 'loop' : '' }} controls playsinline></video>
        @endif
    @elseif($mediaId)
        @php $media = \App\Models\MediaAsset::find($mediaId); @endphp
        @if($media)
        <video src="{{ $media->url }}" class="w-full h-full" {{ $autoplay ? 'autoplay' : '' }} {{ $muted ? 'muted' : '' }} {{ $loop ? 'loop' : '' }} controls></video>
        @endif
    @endif
</div>
