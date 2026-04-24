@extends('admin.layouts.app')

@section('title', ($contentType->exists ? 'Edit' : 'Create') . ' Content Type | Super WMS')
@section('header', ($contentType->exists ? 'Edit' : 'Create') . ' Content Type')

@section('content')
<div class="card p-8">
    <form action="{{ $contentType->exists ? route('admin.content-types.update', $contentType) : route('admin.content-types.store') }}" method="POST" class="space-y-6 max-w-3xl">
        @csrf
        @if($contentType->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $contentType->name) }}" class="field-ui w-full" required>
            </div>
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $contentType->slug) }}" class="field-ui w-full" required>
            </div>
        </div>

        <div class="field-wrapper">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Description</label>
            <textarea name="description" class="field-ui w-full" rows="3">{{ old('description', $contentType->description) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Icon</label>
                <input type="text" name="icon" value="{{ old('icon', $contentType->icon) }}" class="field-ui w-full" placeholder="e.g. file-text, image, wrench">
                <p class="text-xs text-stone/50 mt-1">Lucide icon name for the sidebar.</p>
            </div>
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Layout Template</label>
                <input type="text" name="layout_template" value="{{ old('layout_template', $contentType->layout_template) }}" class="field-ui w-full" placeholder="e.g. service-city-page">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="field-wrapper flex items-center">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-forest">
                    <input type="checkbox" name="is_hierarchical" value="1" {{ old('is_hierarchical', $contentType->is_hierarchical) ? 'checked' : '' }} class="rounded border-stone/50 text-forest focus:ring-forest">
                    Is Hierarchical
                </label>
            </div>
            <div class="field-wrapper flex items-center">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-forest">
                    <input type="checkbox" name="has_archives" value="1" {{ old('has_archives', $contentType->has_archives) ? 'checked' : '' }} class="rounded border-stone/50 text-forest focus:ring-forest">
                    Has Archives
                </label>
            </div>
        </div>

        <div class="field-wrapper">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Schema JSON</label>
            <p class="text-xs text-stone/70 mb-2">Define dynamic fields in JSON format (e.g. [{"name":"hero_image", "type":"image"}])</p>
            <textarea name="schema_json" class="field-ui w-full font-mono text-sm" rows="6">{{ old('schema_json', $contentType->schema_json ? json_encode($contentType->schema_json, JSON_PRETTY_PRINT) : '') }}</textarea>
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-stone/20">
            <a href="{{ route('admin.content-types.index') }}" class="btn-outline px-6 py-2 rounded-sm border border-stone/50 text-forest hover:bg-stone/5">Cancel</a>
            <button type="submit" class="btn-solid bg-forest text-white px-8 py-2 rounded-sm text-sm font-bold uppercase tracking-widest">Save Content Type</button>
        </div>
    </form>
</div>
@endsection
