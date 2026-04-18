@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($page->meta_title ?? $page->page_title) . ' | Lush Landscape Service'"
    :description="$page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->local_intro ?? ''), 155)"
    :canonical="url('/' . $page->slug_final)"
    :ogTitle="$page->og_title ?? null"
    :ogDescription="$page->og_description ?? null"
    :ogImage="$page->heroMedia?->url ?? null"
    :schema="$schema"
    :noindex="!$page->is_indexable"
    :geo="['region' => 'CA-ON', 'placename' => $page->city->name . ', Ontario', 'position' => ($page->city->latitude ?? '43.6532') . ';' . ($page->city->longitude ?? '-79.3832')]"
/>
@endsection
@section('content')

@php
    $phone = \App\Models\Setting::get('phone', '');

    // Divide blocks into layout regions
    $topKeys = ['hero', 'stats_bar', 'service_hero', 'parallax_media_band'];
    $btmKeys = ['trust_badges', 'cta_section', 'split_consultation_panel', 'services_grid'];

    // Legacy rows used section_key; unified rows use block_type
    $getBlockKey = fn($b) => ($b->is_layout_section ?? false) ? ($b->section_key ?? null) : ($b->block_type ?? null);

    $topBlocks  = $blocks->filter(fn($b) => in_array($getBlockKey($b), $topKeys));
    $btmBlocks  = $blocks->filter(fn($b) => in_array($getBlockKey($b), $btmKeys));
    $mainBlocks = $blocks->reject(fn($b) => in_array($getBlockKey($b), array_merge($topKeys, $btmKeys)));
@endphp

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@else
    <section class="bg-white py-32 px-6 text-center">
        <p class="text-ink/50">Service-City landing page content is currently empty. Please configure blocks in the CMS.</p>
    </section>
@endif

@endsection
