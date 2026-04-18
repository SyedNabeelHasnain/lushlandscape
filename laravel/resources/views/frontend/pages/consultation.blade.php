@extends('frontend.layouts.app')

@section('seo')
<x-frontend.seo-head
    title="Project Consultation | Super WMS Service"
    description="Begin your project inquiry and request an on-site consultation with Super WMS Service. Share a few details and our team will follow up with next steps."
    :canonical="url('/consultation')"
    :schema="$schema"
/>
@endsection

@section('content')

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context ?? []" />
    @endforeach
@else
    <section class="bg-white py-32 px-6 text-center">
        <p class="text-ink/50">Consultation page content is currently empty. Please configure blocks in the CMS.</p>
    </section>
@endif

@endsection