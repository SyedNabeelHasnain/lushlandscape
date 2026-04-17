@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($page->meta_title ?? $page->title) . ' | Lush Landscape Service'"
    :description="$page->meta_description ?? $page->excerpt ?? ''"
    :canonical="url('/' . $page->slug)"
    :ogTitle="$page->og_title ?? null"
    :ogDescription="$page->og_description ?? null"
    :ogImage="$page->heroMedia?->url ?? null"
    :noindex="!$page->is_indexable"
    :schema="$schema"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-4xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

@if($page->heroMedia)
<div class="w-full aspect-21/9 overflow-hidden bg-forest/10">
    <x-frontend.media :asset="$page->heroMedia" :alt="$page->title" class="w-full h-full object-cover" fetchpriority="high" loading="eager" />
</div>
@endif

<section class="section-editorial bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-12">
        <h1 class="text-h2 font-heading font-bold text-ink">{{ $page->title }}</h1>
        @if($page->excerpt)<p class="mt-4 text-text-secondary text-lg leading-relaxed">{{ $page->excerpt }}</p>@endif
        @if($page->body)
        <div class="mt-8 prose prose-lg max-w-none text-text leading-relaxed
            prose-headings:font-bold prose-headings:text-text
            prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4
            prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
            prose-p:text-text-secondary prose-p:leading-relaxed
            prose-li:text-text-secondary
            prose-a:text-forest prose-a:underline hover:prose-a:no-underline
            prose-strong:text-text prose-strong:font-semibold">
            {!! $page->body !!}
        </div>
        @endif
    </div>
</section>

@if(isset($blocks) && $blocks->isNotEmpty())
@foreach($blocks as $block)
    <x-frontend.block-renderer :block="$block" :context="$context" />
@endforeach
@endif
@endsection
