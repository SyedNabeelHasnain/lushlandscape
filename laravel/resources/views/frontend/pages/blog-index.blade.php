@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    title="Landscaping Blog | Tips, Costs & Guides | Lush Landscape Service"
    description="Expert landscaping tips, cost guides, project inspiration, and how-to articles for Ontario homeowners. Trusted advice from local professionals."
    :canonical="url('/blog')"
    :schema="$schema"
    :paginator="$posts"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="[['label' => 'Blog']]" />
    </div>
</div>

@foreach($blocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach
@endsection
