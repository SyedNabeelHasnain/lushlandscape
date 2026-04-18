@extends('frontend.layouts.app')

@section('seo')
<x-frontend.seo-head
    :title="($project->meta_title ?: $project->title) . ' | Lush Landscape Service'"
    :description="$project->meta_description ?: Illuminate\Support\Str::limit($project->description ?? '', 155)"
    :canonical="url('/portfolio/' . $project->slug)"
    :ogTitle="$project->title"
    :ogDescription="$project->description"
    :ogImage="$project->heroMedia?->url ?? null"
    :schema="$schema"
/>
@endsection

@section('content')

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@else
    <section class="bg-white py-32 px-6 text-center">
        <p class="text-ink/50">Portfolio project content is currently empty. Please configure blocks in the CMS.</p>
    </section>
@endif

@endsection