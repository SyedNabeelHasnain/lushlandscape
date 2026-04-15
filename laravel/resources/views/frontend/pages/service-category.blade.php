@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($category->meta_title ?? $category->name . ' Services Ontario') . ' | Lush Landscape Service'"
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

@foreach($blocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach
@endsection
