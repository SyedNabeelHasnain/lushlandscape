@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($category->meta_title ?? $category->name . ' Services Our Region') . ' | Super WMS Service'"
    :description="$category->meta_description ?? $category->short_description ?? ''"
    :canonical="url('/services/' . $category->slug_final)"
    :ogTitle="$category->og_title ?? null"
    :ogDescription="$category->og_description ?? null"
    :schema="$schema"
/>
@endsection
@section('content')

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
        <p class="text-ink/50">Service Category content is currently empty. Please configure blocks in the CMS.</p>
    </section>
@endif
@endsection
