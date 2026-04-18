@props([
    'title'          => 'Super WMS',
    'description'    => 'Premium professional construction contractors serving Our Region, Canada. Professional, concrete, and landscape services with 10-year warranty.',
    'canonical'      => null,
    'ogTitle'        => null,
    'ogDescription'  => null,
    'ogImage'        => null,
    'ogImageWidth'   => 1200,
    'ogImageHeight'  => 630,
    'ogType'         => 'website',
    'noindex'        => false,
    'schema'         => null,
    'geo'            => null,
    'articlePublished' => null,
    'articleModified'  => null,
    'articleAuthor'    => null,
    'paginator'      => null,
])
@php
    $twitterHandle = \App\Models\Setting::get('twitter_handle', '');
    $siteName      = \App\Models\Setting::get('site_name', 'Super WMS');
    $defaultOgImg  = \App\Models\Setting::get('default_og_image', '');
    $resolvedImg   = $ogImage ?? ($defaultOgImg ? asset($defaultOgImg) : null);
    // Default GEO for Our Region
    $geoData = $geo ?? ['region' => 'US-NY', 'placename' => 'Our Region', 'position' => '40.7128;-74.0060'];

    // Pagination-aware canonical + rel links
    $paginatorObj = $paginator;
    $currentPage  = $paginatorObj?->currentPage() ?? 1;
    $lastPage     = $paginatorObj?->lastPage() ?? 1;
    $baseCanonical = $canonical ?? url()->current();
    // Strip existing page param for clean base
    $cleanBase    = preg_replace('/[?&]page=\d+/', '', $baseCanonical);
    $cleanBase    = rtrim($cleanBase, '?&');
    $resolvedCanonical = $currentPage > 1 ? $cleanBase . '?page=' . $currentPage : $cleanBase;
    $prevUrl = ($currentPage > 1) ? ($currentPage === 2 ? $cleanBase : $cleanBase . '?page=' . ($currentPage - 1)) : null;
    $nextUrl = ($currentPage < $lastPage) ? $cleanBase . '?page=' . ($currentPage + 1) : null;
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $resolvedCanonical }}">
@if($prevUrl)<link rel="prev" href="{{ $prevUrl }}">@endif
@if($nextUrl)<link rel="next" href="{{ $nextUrl }}">@endif
<meta name="robots" content="{{ $noindex ? 'noindex, nofollow' : 'index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1' }}">

{{-- Open Graph --}}
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $ogTitle ?? $title }}">
<meta property="og:description" content="{{ $ogDescription ?? $description }}">
<meta property="og:url" content="{{ $resolvedCanonical }}">
<meta property="og:locale" content="en_CA">
@if($resolvedImg)
<meta property="og:image" content="{{ $resolvedImg }}">
<meta property="og:image:width" content="{{ $ogImageWidth }}">
<meta property="og:image:height" content="{{ $ogImageHeight }}">
<meta property="og:image:alt" content="{{ $ogTitle ?? $title }}">
@endif
@if($ogType === 'article')
@if($articlePublished)<meta property="article:published_time" content="{{ $articlePublished }}">@endif
@if($articleModified)<meta property="article:modified_time" content="{{ $articleModified }}">@endif
@if($articleAuthor)<meta property="article:author" content="{{ $articleAuthor }}">@endif
<meta property="article:section" content="Professional">
@endif

{{-- Twitter / X Card --}}
<meta name="twitter:card" content="summary_large_image">
@if($twitterHandle)<meta name="twitter:site" content="{{ $twitterHandle }}">@endif
<meta name="twitter:title" content="{{ $ogTitle ?? $title }}">
<meta name="twitter:description" content="{{ $ogDescription ?? $description }}">
@if($resolvedImg)<meta name="twitter:image" content="{{ $resolvedImg }}">@endif

{{-- Theme color for browser chrome --}}
<meta name="theme-color" content="#27452B">

{{-- GEO / ICBM tags for local SEO --}}
<meta name="geo.region" content="{{ $geoData['region'] }}">
<meta name="geo.placename" content="{{ $geoData['placename'] }}">
<meta name="geo.position" content="{{ $geoData['position'] }}">
<meta name="ICBM" content="{{ str_replace(';', ',', $geoData['position']) }}">

{{-- JSON-LD Schema --}}
@if($schema){!! $schema !!}@endif

<script>
    window.WMS_CONFIG = {
        maps_url: "{{ config('services.maps.js_url', 'https://maps.googleapis.com/maps/api/js') }}"
    };
</script>
