@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    title="Landscaping Services Ontario | Lush Landscape Service"
    description="Explore our complete range of landscaping construction services including interlocking, concrete, natural stone, softscaping, and more across Ontario. 10-year warranty."
    :canonical="url('/services')"
    :schema="$schema"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="[['label' => 'Services']]" />
    </div>
</div>

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@else
    <section class="bg-white py-32 px-6 text-center">
        <p class="text-ink/50">Services Hub content is currently empty. Please configure blocks in the CMS.</p>
    </section>
@endif
@endsection
