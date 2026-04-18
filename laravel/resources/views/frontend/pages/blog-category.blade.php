@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($category->meta_title ?? $category->name . ' Blog Articles') . ' | Super WMS Blog'"
    :description="$category->meta_description ?? $category->short_description ?? ''"
    :canonical="route('blog.category', $category->slug)"
    :ogTitle="$category->og_title ?? null"
    :ogDescription="$category->og_description ?? null"
    :ogImage="$category->image?->url ?? null"
    :schema="$schema"
    :paginator="$posts"
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
