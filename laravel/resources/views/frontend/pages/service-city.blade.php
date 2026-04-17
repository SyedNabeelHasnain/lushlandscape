@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($page->meta_title ?? $page->page_title) . ' | Lush Landscape Service'"
    :description="$page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->local_intro ?? ''), 155)"
    :canonical="url('/' . $page->slug_final)"
    :ogTitle="$page->og_title ?? null"
    :ogDescription="$page->og_description ?? null"
    :ogImage="$page->heroMedia?->url ?? null"
    :schema="$schema"
    :noindex="!$page->is_indexable"
    :geo="['region' => 'CA-ON', 'placename' => $page->city->name . ', Ontario', 'position' => ($page->city->latitude ?? '43.6532') . ';' . ($page->city->longitude ?? '-79.3832')]"
/>
@endsection
@section('content')

@php
    $phone = \App\Models\Setting::get('phone', '');

    // Divide blocks into layout regions
    $topKeys = ['hero', 'stats_bar', 'service_hero', 'parallax_media_band'];
    $btmKeys = ['trust_badges', 'cta_section', 'split_consultation_panel', 'services_grid'];

    // Legacy rows used section_key; unified rows use block_type
    $getBlockKey = fn($b) => ($b->is_layout_section ?? false) ? ($b->section_key ?? null) : ($b->block_type ?? null);

    $topBlocks  = $blocks->filter(fn($b) => in_array($getBlockKey($b), $topKeys));
    $btmBlocks  = $blocks->filter(fn($b) => in_array($getBlockKey($b), $btmKeys));
    $mainBlocks = $blocks->reject(fn($b) => in_array($getBlockKey($b), array_merge($topKeys, $btmKeys)));
@endphp

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

{{-- Top Regions (Full Width) --}}
@foreach($topBlocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach

{{-- Main content + sidebar --}}
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-12 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Main column: page builder blocks --}}
        <div class="lg:col-span-2 space-y-12">
            @foreach($mainBlocks as $block)
                <x-frontend.block-renderer :block="$block" :context="$context" />
            @endforeach
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick quote CTA --}}
            <div class="bg-forest p-6 text-white sticky top-24">
                <h3 class="text-lg font-bold mb-2">Book a Consultation</h3>
                <p class="text-white/70 text-sm mb-5">Professional landscaping in {{ $page->city->name }}. Clear scope and thoughtful material direction.</p>
                <a href="/contact"
                   class="block bg-white text-forest font-bold py-3.5 text-center hover:bg-white/90 transition mb-3 text-sm">
                    Book a Consultation
                </a>
                @if($phone)
                <a href="tel:{{ preg_replace('/[^+\d]/', '', $phone) }}"
                   class="flex items-center justify-center gap-2 text-white/70 hover:text-white text-sm transition">
                    <i data-lucide="phone" class="w-4 h-4"></i>{{ $phone }}
                </a>
                @endif
            </div>

            {{-- City switcher: same service in other cities --}}
            @if(isset($switcherCities) && $switcherCities->count() > 1)
            <div class="bg-white border border-stone p-6">
                <h3 class="text-sm font-bold text-text mb-3">{{ $page->service->name }} in Other Cities</h3>
                <x-frontend.city-switcher
                    :currentCity="$page->city"
                    :allCities="$switcherCities"
                    :serviceSlug="\Illuminate\Support\Str::slug($page->service->name)"
                    mode="service-city"
                />
            </div>
            @endif

            {{-- Other services in this city --}}
            @if($relatedPages->count() > 0)
            <div class="bg-white border border-stone p-6">
                <h3 class="text-sm font-bold text-text mb-4">Other Services in {{ $page->city->name }}</h3>
                <ul class="space-y-2">
                    @foreach($relatedPages as $rp)
                    <li>
                        <a href="{{ $rp->frontend_url }}" class="flex items-center gap-2 text-sm text-text-secondary hover:text-forest transition">
                            <i data-lucide="chevron-right" class="w-4 h-4 text-forest shrink-0"></i>
                            {{ $rp->service->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Bottom Regions (Full Width) --}}
@foreach($btmBlocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach

@endsection
