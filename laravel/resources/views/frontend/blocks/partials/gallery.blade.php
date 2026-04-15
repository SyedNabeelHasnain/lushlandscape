{{-- Block: gallery --}}
@php
    $mediaIds = $content['media_ids'] ?? [];
    $layout = $content['layout'] ?? 'grid';
    $columns = $content['columns'] ?? '3';
    $lightbox = $content['lightbox'] ?? true;
    $colMap = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-2 lg:grid-cols-3', '4' => 'md:grid-cols-2 lg:grid-cols-4'];
    $colClass = $colMap[$columns] ?? 'md:grid-cols-2 lg:grid-cols-3';
    $media = !empty($mediaIds) ? \App\Models\MediaAsset::whereIn('id', $mediaIds)->get()->sortBy(fn($m) => array_search($m->id, $mediaIds))->values() : collect();
@endphp
@if($media->isNotEmpty())
<div class="grid {{ $colClass }} gap-4">
    @foreach($media as $img)
    <div class="overflow-hidden rounded-lg aspect-square">
        <x-frontend.media :asset="$img" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
    </div>
    @endforeach
</div>
@endif
