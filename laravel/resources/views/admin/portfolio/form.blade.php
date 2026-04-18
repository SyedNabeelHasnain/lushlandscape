@extends('admin.layouts.app')
@section('title', isset($project) ? 'Edit Project' : 'Create Project')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($project) ? 'Edit: ' . $project->title : 'Create Portfolio Project'" :viewUrl="isset($project) ? url('/portfolio') : null" />
<form method="POST" action="{{ isset($project) ? route('admin.portfolio.update', $project) : route('admin.portfolio.store') }}" data-ajax-form="true" data-success-message="{{ isset($project) ? 'Project updated successfully.' : 'Project created.' }}">
    @csrf
    @if(isset($project)) @method('PUT') @endif
    <x-admin.card title="Project Details">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-input name="title" label="Title" :value="$project->title ?? ''" required />
            <x-admin.form-input name="slug" label="Slug" :value="$project->slug ?? ''" required />
            <x-admin.form-select name="city_id" label="City" :options="$cities->toArray()" :value="$project->city_id ?? ''" />
            <x-admin.form-select name="service_id" label="Service" :options="$services->toArray()" :value="$project->service_id ?? ''" />
            <x-admin.form-input name="project_type" label="Project Type" :value="$project->project_type ?? ''" />
            <x-admin.form-input name="neighborhood" label="Neighborhood" :value="$project->neighborhood ?? ''" />
            <x-admin.form-input name="project_value_range" label="Project Value Range" :value="$project->project_value_range ?? ''" help="e.g. $5,000–$15,000" />
            <x-admin.form-input name="project_duration" label="Project Duration" :value="$project->project_duration ?? ''" help="e.g. 3 days" />
        </div>
        <div class="mt-5"><x-admin.form-textarea name="description" label="Short Description" :value="$project->description ?? ''" :rows="3" /></div>
        <div class="mt-5"><x-admin.form-textarea name="body" label="Full Description" :value="$project->body ?? ''" :rows="8" /></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
            <x-admin.form-input name="video_url" label="Video URL" :value="$project->video_url ?? ''" />
            <x-admin.form-input name="completion_date" label="Completion Date" type="date" :value="$project->completion_date ?? ''" />
            <x-admin.form-input name="meta_title" label="Meta Title" :value="$project->meta_title ?? ''" />
            <x-admin.form-input name="sort_order" label="Order" type="number" :value="$project->sort_order ?? 0" />
        </div>
        <div class="mt-5"><x-admin.form-textarea name="meta_description" label="Meta Description" :value="$project->meta_description ?? ''" :rows="2" /></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
            <x-admin.form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published','archived'=>'Archived']" :value="$project->status ?? 'draft'" required />
            <x-admin.form-toggle name="is_featured" label="Featured" :checked="$project->is_featured ?? false" />
        </div>
    </x-admin.card>

    <x-admin.card title="Images" class="mt-6">
        <div class="space-y-6">
            <x-admin.form-media
                name="hero_media_id"
                label="Hero Image"
                :mediaAsset="$project->heroMedia ?? null"
                help="Main project image shown in listings and at page top."
                :croppable="true" />
            <x-admin.form-media
                name="before_image_id"
                label="Before Image"
                :mediaAsset="$project->beforeImage ?? null"
                help="Before state for before/after slider." />
            <x-admin.form-media
                name="after_image_id"
                label="After Image"
                :mediaAsset="$project->afterImage ?? null"
                help="After state for before/after slider." />
        </div>
        <div class="mt-6">
            <label class="block text-sm font-medium text-text mb-1.5">Gallery Media IDs</label>
            <input type="text" name="gallery_media_ids"
                value="{{ old('gallery_media_ids', isset($project) ? implode(', ', $project->gallery_media_ids ?? []) : '') }}"
                placeholder="e.g. 12, 15, 23, 31"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
            <p class="text-xs text-text-secondary mt-1.5">Comma-separated Media Asset IDs for the gallery carousel.</p>
        </div>
    </x-admin.card>

    @if(isset($project))
    <x-admin.card title="Content Blocks" class="mt-6">
        <p class="text-xs text-text-secondary mb-4">Add custom content blocks to this project page: process steps, feature lists, testimonials, CTAs, and more.</p>
        @php
            $existingBlocks = isset($blocks) ? $blocks->values()->all() : [];
        @endphp
        <x-admin.block-editor
            pageType="portfolio_project"
            :pageId="$project->id"
            :blocks="$existingBlocks"
            :blockTypes="$blockTypes ?? []"
        />
    </x-admin.card>
    @endif

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <button type="submit" data-loading-label="Saving…" class="bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-6 rounded-xl transition text-sm">{{ isset($project) ? 'Update' : 'Create' }}</button>
        <a href="{{ route('admin.portfolio.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-center text-sm text-text-secondary hover:bg-gray-50 transition">Cancel</a>
    </div>
</form>
@endsection
