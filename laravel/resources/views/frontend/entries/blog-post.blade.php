@extends('frontend.layouts.app')

@php
$post = clone $entry;
$post->title = $entry->title;
$post->excerpt = $entry->data['excerpt'] ?? '';
$post->body = $entry->data['body'] ?? '';
$post->featuredImage = $entry->data['featured_image_id'] ? \App\Models\MediaAsset::find($entry->data['featured_image_id']) : null;
$post->category = clone $entry->terms->first();
@endphp

@section('seo')
<x-frontend.seo-head
    :title="($post->meta_title ?? $post->title) . ' | Super WMS Blog'"
    :description="$post->meta_description ?? $post->excerpt ?? ''"
    :canonical="url('/blog/' . $post->slug)"
    :ogTitle="$post->og_title ?? null"
    :ogDescription="$post->og_description ?? null"
    :ogImage="$post->heroMedia?->url ?? null"
    ogType="article"
    :articlePublished="$post->published_at?->toIso8601String()"
    :articleModified="$post->updated_at?->toIso8601String()"
    :articleAuthor="$post->author_name ?? null"
    :schema="$schema"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-4xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

<article class="bg-white">
    {{-- Hero --}}
    @if($post->heroMedia)
    <div class="w-full aspect-21/9 overflow-hidden bg-forest/10">
        <img src="{{ $post->heroMedia->url }}" alt="{{ $post->heroMedia->default_alt_text ?? $post->title }}" class="w-full h-full object-cover" width="1400" height="600" fetchpriority="high" decoding="async">
    </div>
    @endif

    <div class="max-w-4xl mx-auto px-6 lg:px-12 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
            {{-- Main content --}}
            <div class="lg:col-span-3">
                <header class="mb-8">
                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @if($post->category ?? null)
                        <a href="{{ route('blog.category', $post->category->slug) }}" class="text-xs bg-forest/10 text-forest px-3 py-1.5 font-semibold hover:bg-forest hover:text-white transition">{{ $post->category->name }}</a>
                        @endif
                        <time class="text-sm text-text-secondary" datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at?->format('F j, Y') }}</time>
                        @if($post->read_time ?? null)<span class="text-sm text-text-secondary">· {{ $post->read_time }} min read</span>@endif
                    </div>
                    <h1 class="text-3xl md:text-4xl font-heading font-bold tracking-tight text-text leading-tight">{{ $post->title }}</h1>
                    @if($post->excerpt)<p class="mt-4 text-lg text-text-secondary leading-relaxed">{{ $post->excerpt }}</p>@endif
                </header>

                {{-- Author --}}
                <x-frontend.author-bio :author="$post->author ?? null" :publishedAt="$post->published_at" :updatedAt="$post->updated_at" />

                {{-- TOC --}}
                <div class="mt-8">
                    <x-frontend.toc />
                </div>

                {{-- Content --}}
                <div class="prose prose-lg max-w-none text-text leading-relaxed mt-8
                    prose-headings:font-bold prose-headings:text-text
                    prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4
                    prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
                    prose-p:text-text-secondary prose-p:leading-relaxed
                    prose-li:text-text-secondary
                    prose-a:text-forest prose-a:underline hover:prose-a:no-underline
                    prose-strong:text-text prose-strong:font-semibold
                    prose-blockquote:border-forest prose-blockquote:bg-forest/10 prose-blockquote:rounded-r-xl prose-blockquote:py-1">
                    {!! $post->body !!}
                </div>

                {{-- Tags / share --}}
                <div class="mt-10 pt-8 border-t border-stone flex flex-col sm:flex-row justify-between items-start gap-4">
                    @if(!empty($post->tags ?? []))
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <span class="text-xs bg-forest/10 text-forest px-3 py-1.5 ">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    @endif
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="text-xs text-text-secondary">Share:</span>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on X" class="w-8 h-8 bg-forest/6  flex items-center justify-center hover:bg-forest/5 transition"><i data-lucide="twitter" class="w-4 h-4 text-text-secondary"></i></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook" class="w-8 h-8 bg-forest/6  flex items-center justify-center hover:bg-forest/5 transition"><i data-lucide="facebook" class="w-4 h-4 text-text-secondary"></i></a>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-5">
                    <div class="bg-forest p-5 text-white">
                        <h3 class="text-sm font-bold mb-2">Book a Consultation</h3>
                        <p class="text-white/70 text-xs mb-4 leading-relaxed">Plan your professional project with a clear scope and thoughtful material direction.</p>
                        <a href="{{ url('/contact') }}" class="block bg-white text-forest font-bold py-3  text-center hover:bg-white/90 transition text-sm">Book a Consultation</a>
                    </div>
                    @if(isset($popularPosts) && $popularPosts->count() > 0)
                    <div class="bg-cream border border-stone p-5">
                        <h3 class="text-sm font-bold text-text mb-4">Popular Articles</h3>
                        <ul class="space-y-4">
                            @foreach($popularPosts as $pp)
                            <li>
                                <a href="{{ route('blog.show', $pp->slug) }}" class="text-sm text-text hover:text-forest transition font-medium line-clamp-2">{{ $pp->title }}</a>
                                <p class="text-xs text-text-secondary mt-0.5">{{ $pp->published_at?->format('M j, Y') }}</p>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</article>

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@endif

@endsection
