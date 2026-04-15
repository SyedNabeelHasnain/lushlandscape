{{-- Block: image --}}
@php
    $mediaId = $content['media_id'] ?? null;
    $alt = $content['alt'] ?? '';
    $caption = $content['caption'] ?? '';
    $linkUrl = $content['link_url'] ?? '';
    $aspectRatio = $content['aspect_ratio'] ?? 'auto';
    $rounded = $content['rounded'] ?? false;
    $aspectMap = ['16:9' => 'aspect-video', '4:3' => 'aspect-[4/3]', '1:1' => 'aspect-square', '3:2' => 'aspect-[3/2]'];
    $aspectClass = $aspectRatio !== 'auto' ? ($aspectMap[$aspectRatio] ?? '') : '';
    $roundedClass = $rounded ? 'rounded-xl' : '';
    $media = $mediaId ? \App\Models\MediaAsset::find($mediaId) : null;
@endphp
@if($media)
<figure class="{{ $roundedClass }}">
    @if($linkUrl)<a href="{{ $linkUrl }}">@endif
    <div class="{{ $aspectClass }}">
        <x-frontend.media :asset="$media" :alt="$alt" class="w-full h-full object-cover {{ $roundedClass }}" />
    </div>
    @if($linkUrl)</a>@endif
    @if($caption)<figcaption class="mt-2 text-sm text-text-secondary text-center">{{ $caption }}</figcaption>@endif
</figure>
@endif
