@props([
    'asset' => null,
    'placement' => null,
    'alt' => null,
    'class' => '',
    'loading' => null,
    'fetchpriority' => null,
    'decoding' => 'async',
])

@php
    $asset = $asset instanceof \App\Models\MediaAsset ? $asset : null;
    $placement = $placement instanceof \App\Models\MediaPlacement ? $placement : null;
    $url = $asset?->url ?? '';

    $resolvedAlt = '';
    if ($alt !== null) {
        $resolvedAlt = (string) $alt;
    } elseif ($placement) {
        $resolvedAlt = (string) $placement->alt_text;
    } else {
        $resolvedAlt = (string) ($asset?->default_alt_text ?? '');
    }

    $focal = null;
    if ($placement) {
        $focal = data_get($placement->crop_data ?? [], 'focal_point')
            ?? data_get($placement->crop_data ?? [], 'focal')
            ?? null;
    }
    if (!is_array($focal)) {
        $focal = $asset?->focal_point;
    }

    $x = data_get($focal ?? [], 'x');
    $y = data_get($focal ?? [], 'y');

    if ($x === null) {
        $x = data_get($focal ?? [], 'left');
    }
    if ($y === null) {
        $y = data_get($focal ?? [], 'top');
    }

    $x = is_numeric($x) ? (float) $x : 0.5;
    $y = is_numeric($y) ? (float) $y : 0.5;

    if ($x > 1) {
        $x = $x / 100;
    }
    if ($y > 1) {
        $y = $y / 100;
    }

    $x = min(1, max(0, $x));
    $y = min(1, max(0, $y));
    $objectPosition = round($x * 100, 2).'% '.round($y * 100, 2).'%';

    $resolvedLoading = $loading !== null
        ? (string) $loading
        : (string) ($placement?->loading ?? 'lazy');

    $resolvedFetch = $fetchpriority !== null
        ? (string) $fetchpriority
        : (string) ($placement?->fetchpriority ?? '');
@endphp

@if($url)
    <img
        src="{{ $url }}"
        alt="{{ $resolvedAlt }}"
        class="{{ $class }}"
        @if(str_contains($class, 'object-cover') || str_contains($class, 'object-contain')) style="object-position: {{ $objectPosition }};" @endif
        loading="{{ $resolvedLoading }}"
        @if($resolvedFetch) fetchpriority="{{ $resolvedFetch }}" @endif
        decoding="{{ $decoding }}"
    >
@endif

