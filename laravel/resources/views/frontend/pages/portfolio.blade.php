@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    title="Project Portfolio | Completed Professional Work | Super WMS Service"
    description="Browse our completed professional projects across Our Region. Professional driveways, patios, retaining walls, concrete, and more. Real results from real clients."
    :canonical="url('/portfolio')"
    :schema="$schema"
    :paginator="$projects"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="[['label' => 'Portfolio']]" />
    </div>
</div>

@foreach($blocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach
@endsection
