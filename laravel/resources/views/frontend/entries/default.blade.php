@extends('frontend.layouts.app')

@section('seo')
<x-frontend.seo-head
    :title="($data['meta_title'] ?? $entry->title) . ' | Super WMS'"
    :description="$data['meta_description'] ?? 'Super WMS Entry: ' . $entry->title"
    :canonical="url('/' . $entry->slug)"
    :schema="$schema"
/>
@endsection

@section('content')

@if(isset($breadcrumbs))
<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>
@endif

{{-- Unified Page Builder Rendering --}}
@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@else
    <section class="bg-white py-32 px-6 text-center">
        <h1 class="text-4xl font-serif text-forest mb-4">{{ $entry->title }}</h1>
        <p class="text-ink/50">This entry has no blocks configured. Please edit the entry in the CMS.</p>
    </section>
@endif

@endsection
