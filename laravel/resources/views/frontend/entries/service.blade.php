@extends('frontend.layouts.app')

@php
$service = clone $entry;
$service->name = $entry->title;
$service->service_summary = $entry->data['service_summary'] ?? '';
$service->service_body = $entry->data['service_body'] ?? [];
$service->heroMedia = $entry->data['hero_media_id'] ? \App\Models\MediaAsset::find($entry->data['hero_media_id']) : null;
$service->category = clone $entry->terms->first();
@endphp

@section('seo')
<x-frontend.seo-head
    :title="($service->default_meta_title ?? $service->name . ' in Our Region') . ' | Super WMS Service'"
    :description="$service->default_meta_description ?? $service->service_summary ?? ''"
    :ogTitle="$service->default_og_title ?? null"
    :ogDescription="$service->default_og_description ?? null"
    :ogImage="$service->heroMedia?->url ?? null"
    :canonical="url('/services/' . ($service->category?->slug ?? '') . '/' . $service->slug)"
    :schema="$schema"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

{{-- Unified Page Builder Rendering --}}
@foreach($blocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach

@endsection
