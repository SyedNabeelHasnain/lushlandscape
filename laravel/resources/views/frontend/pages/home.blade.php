@extends('frontend.layouts.app')
@section('seo')
@php
    $siteName = \App\Models\Setting::get('site_name', 'Lush Landscape Service');
    $tagline = \App\Models\Setting::get('site_tagline', 'Landscaping Construction Contractors in Ontario');
    $defaultTitle = $siteName . ' | ' . $tagline;
    $defaultDesc = 'Premium interlocking, concrete, and landscape construction across Ontario. 10-year warranty. Consultation-led design and build.';

    // Social variables
    $ogImageId = \App\Models\Setting::get('seo_home_og_image_id');
    $ogImagePath = '';
    if ($ogImageId) {
        $asset = \App\Models\MediaAsset::find($ogImageId);
        if ($asset) $ogImagePath = $asset->url;
    }
@endphp
<x-frontend.seo-head
    :title="\App\Models\Setting::get('seo_home_title') ?: $defaultTitle"
    :description="\App\Models\Setting::get('seo_home_description') ?: $defaultDesc"
    :ogTitle="\App\Models\Setting::get('seo_home_og_title') ?: \App\Models\Setting::get('seo_home_title', $defaultTitle)"
    :ogDescription="\App\Models\Setting::get('seo_home_og_description') ?: \App\Models\Setting::get('seo_home_description', $defaultDesc)"
    :ogImage="$ogImagePath"
    :canonical="url('/')"
    :schema="$schema"
/>
@endsection
@section('content')

{{-- Unified Block Rendering — all blocks rendered dynamically --}}
@foreach($blocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach

@endsection
