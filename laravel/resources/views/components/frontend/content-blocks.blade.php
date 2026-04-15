@props(['blocks', 'context' => []])
{{-- Renders an ordered collection of ContentBlock models with universal wrapper styling --}}
@php
    // Pre-load all media assets in one query to eliminate N+1
    $__mediaIds = [];
    foreach ($blocks as $__b) {
        $__c = $__b->content ?? [];
        foreach (['media_id', 'bg_media_id', 'before_media_id', 'after_media_id'] as $__k) {
            if (!empty($__c[$__k])) $__mediaIds[] = (int) $__c[$__k];
        }
        foreach (['items', 'slides', 'images', 'logos', 'badges', 'cards'] as $__arr) {
            foreach ($__c[$__arr] ?? [] as $__item) {
                if (!empty($__item['media_id'])) $__mediaIds[] = (int) $__item['media_id'];
            }
        }
    }
    $__mediaLookup = !empty($__mediaIds)
        ? \App\Models\MediaAsset::whereIn('id', array_unique($__mediaIds))->get()->keyBy('id')
        : collect();
@endphp
@foreach($blocks as $block)
@php
    $view    = 'frontend.blocks.' . str_replace('_', '-', $block->block_type);
    $content = \App\Services\BlockBuilderService::parseDynamicContent($block->content ?? [], $context);
    $w       = $content['_wrapper'] ?? [];

    $wBg = match($w['bg_color'] ?? 'none') {
        'white'  => 'bg-white',
        'cream'  => 'bg-cream',
        'gray'   => 'bg-gray-50',
        'forest' => 'bg-forest',
        'dark'   => 'bg-luxury-dark',
        default  => '',
    };
    $wText = match($w['text_color'] ?? 'default') {
        'white'  => 'text-white',
        'dark'   => 'text-gray-900',
        'forest' => 'text-forest',
        default  => '',
    };
    $wPy = match($w['padding_y'] ?? 'none') {
        'sm' => 'py-4',  'md' => 'py-8',  'lg' => 'py-12',  'xl' => 'py-20',  default => '',
    };
    $wPx = match($w['padding_x'] ?? 'none') {
        'sm' => 'px-4',  'md' => 'px-6 lg:px-8',  'lg' => 'px-8 lg:px-12',  default => '',
    };
    $wMt = match($w['margin_top'] ?? 'none') {
        'sm' => 'mt-4',  'md' => 'mt-8',  'lg' => 'mt-12',  'xl' => 'mt-20',  default => '',
    };
    $wMb = match($w['margin_bottom'] ?? 'none') {
        'sm' => 'mb-4',  'md' => 'mb-8',  'lg' => 'mb-12',  'xl' => 'mb-20',  default => '',
    };
    $wMax = match($w['max_width'] ?? 'full') {
        'xl' => 'max-w-7xl mx-auto',  'lg' => 'max-w-5xl mx-auto',  'md' => 'max-w-3xl mx-auto',  'sm' => 'max-w-xl mx-auto',  default => '',
    };
    $wRound = !empty($w['rounded']) ? 'rounded-2xl overflow-hidden' : '';

    $hasWrapper = $wBg || $wText || $wPy || $wPx || $wMt || $wMb || $wMax || $wRound;
    $wrapperCls = trim(implode(' ', array_filter([$wBg, $wText, $wPy, $wPx, $wMt, $wMb, $wMax, $wRound])));
@endphp
@continue(!$block->is_enabled)
@if(view()->exists($view))
    @if($hasWrapper)
    <div class="{{ $wrapperCls }}">
        @include($view, ['content' => $content, 'mediaLookup' => $__mediaLookup])
    </div>
    @else
        @include($view, ['content' => $content, 'mediaLookup' => $__mediaLookup])
    @endif
@endif
@endforeach
