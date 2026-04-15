@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    title="Service Areas Ontario | Landscaping Contractors Near You | Lush Landscape Service"
    description="We serve homeowners and businesses throughout Ontario including Hamilton, Burlington, Oakville, Mississauga, Milton, Toronto, Vaughan, Richmond Hill, Georgetown, and Brampton."
    :canonical="url('/locations')"
    :schema="$schema"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="[['label' => 'Service Areas']]" />
    </div>
</div>

@foreach($blocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach
@endsection
