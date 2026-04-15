@php
    $asset = !empty($content['media_id']) ? ($mediaLookup[$content['media_id']] ?? null) : null;
    $widthClass = match($content['width'] ?? 'full') {
        'large'  => 'max-w-4xl',
        'medium' => 'max-w-2xl',
        'small'  => 'max-w-lg',
        default  => 'max-w-7xl',
    };
@endphp
@if($asset)
<div class="px-4 py-4">
    <figure class="{{ $widthClass }} mx-auto">
        <x-frontend.media :asset="$asset" :alt="$content['alt'] ?? ''" class="w-full" />
        @if(!empty($content['caption']))
        <figcaption class="text-xs text-text-secondary text-center mt-2">{{ $content['caption'] }}</figcaption>
        @endif
    </figure>
</div>
@endif
