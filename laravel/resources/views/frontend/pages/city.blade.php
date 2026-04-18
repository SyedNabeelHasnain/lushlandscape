@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($city->default_meta_title ?? 'Landscaping Services in ' . $city->name . ', Ontario') . ' | Lush Landscape Service'"
    :description="$city->default_meta_description ?? 'Professional landscaping construction services in ' . $city->name . ', Ontario. Interlocking, concrete, hardscaping, and more. Consultation-led design and build.'"
    :canonical="url('/landscaping-' . $city->slug_final)"
    :ogImage="$city->heroMedia?->url ?? null"
    :schema="$schema"
    :geo="['region' => 'CA-ON', 'placename' => $city->name . ', Ontario, Canada', 'position' => ($city->latitude ?? '43.6532') . ';' . ($city->longitude ?? '-79.3832')]"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
        @if(isset($allCities) && $allCities->count() > 1)
        <div class="w-full sm:w-56 shrink-0">
            <x-frontend.city-switcher
                :currentCity="$city"
                :allCities="$allCities"
                mode="city-landing"
            />
        </div>
        @endif
    </div>
</div>

{{-- Unified Page Builder Rendering --}}
@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@else
    <section class="bg-white py-32 px-6 text-center">
        <p class="text-ink/50">City landing page content is currently empty. Please configure blocks in the CMS.</p>
    </section>
@endif

@endsection
