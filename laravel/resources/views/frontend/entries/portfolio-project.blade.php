@extends('frontend.layouts.app')

@php
$project = clone $entry;
$project->title = $entry->title;
$project->description = $entry->data['description'] ?? '';
$project->body = $entry->data['body'] ?? '';
$project->heroMedia = $entry->data['hero_media_id'] ? \App\Models\MediaAsset::find($entry->data['hero_media_id']) : null;
$project->beforeImage = $entry->data['before_image_id'] ? \App\Models\MediaAsset::find($entry->data['before_image_id']) : null;
$project->afterImage = $entry->data['after_image_id'] ? \App\Models\MediaAsset::find($entry->data['after_image_id']) : null;
$project->gallery_media_ids = $entry->data['gallery_media_ids'] ?? [];
$project->category = clone $entry->terms->first();
@endphp

@section('seo')
<x-frontend.seo-head
    :title="($project->meta_title ?: $project->title) . ' | Super WMS Service'"
    :description="$project->meta_description ?: Illuminate\Support\Str::limit($project->description ?? '', 155)"
    :canonical="url('/portfolio/' . $project->slug)"
    :ogTitle="$project->title"
    :ogDescription="$project->description"
    :ogImage="$project->heroMedia?->url ?? null"
    :schema="$schema"
/>
@endsection

@section('content')

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@else
    <section class="bg-white py-32 px-6 text-center">
        <p class="text-ink/50">Portfolio project content is currently empty. Please configure blocks in the CMS.</p>
    </section>
@endif

@endsection