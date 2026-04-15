@php
    $bgColor = $content['bg_color'] ?? 'white';
    $bgAsset = !empty($content['bg_media_id']) ? ($mediaLookup[$content['bg_media_id']] ?? null) : null;
    $padding = match($content['padding'] ?? 'md') { 'sm' => 'py-6 px-4', 'lg' => 'py-14 px-6 md:px-10', 'xl' => 'py-20 px-8 md:px-14', default => 'py-10 px-5 md:px-8' };
    $rounded = !empty($content['rounded']) ? ' overflow-hidden' : '';
    $maxW    = match($content['max_width'] ?? 'full') { 'xl' => 'max-w-7xl', 'lg' => 'max-w-5xl', 'md' => 'max-w-3xl', default => 'max-w-7xl' };
    $bgCls   = match($bgColor) { 'cream' => 'bg-cream', 'forest' => 'bg-forest text-white', 'gray' => 'bg-cream', 'dark' => 'bg-luxury-dark text-white', default => 'bg-white' };
@endphp
@if(!empty($content['html']))
<div class="{{ $bgCls }} {{ $rounded }} relative {{ $bgAsset ? '' : '' }}">
    @if($bgAsset)
    <div class="absolute inset-0">
        <img src="{{ $bgAsset->url }}" alt="{{ $bgAsset->alt_text ?? 'Background image' }}" class="w-full h-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
    @endif
    <div class="{{ $maxW }} mx-auto {{ $padding }} relative z-10 {{ $bgAsset ? 'text-white' : '' }}">
        <div class="prose prose-sm max-w-none {{ $bgAsset || in_array($bgColor, ['forest', 'dark']) ? 'prose-invert' : 'prose-forest' }}">
            {!! $content['html'] !!}
        </div>
    </div>
</div>
@endif
