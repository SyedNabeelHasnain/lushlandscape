@extends('admin.layouts.app')

@section('title', ($taxonomy->exists ? 'Edit' : 'Create') . ' Taxonomy | Super WMS')
@section('header', ($taxonomy->exists ? 'Edit' : 'Create') . ' Taxonomy')

@section('content')
<div class="card p-8">
    <form action="{{ $taxonomy->exists ? route('admin.taxonomies.update', $taxonomy) : route('admin.taxonomies.store') }}" method="POST" class="space-y-6 max-w-3xl">
        @csrf
        @if($taxonomy->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $taxonomy->name) }}" class="field-ui w-full" required>
            </div>
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $taxonomy->slug) }}" class="field-ui w-full" required>
            </div>
        </div>

        <div class="field-wrapper">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Description</label>
            <textarea name="description" class="field-ui w-full" rows="3">{{ old('description', $taxonomy->description) }}</textarea>
        </div>

        <div class="field-wrapper flex items-center pt-4">
            <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-forest">
                <input type="hidden" name="is_hierarchical" value="0">
                <input type="checkbox" name="is_hierarchical" value="1" {{ old('is_hierarchical', $taxonomy->is_hierarchical ?? true) ? 'checked' : '' }} class="rounded border-stone/50 text-forest focus:ring-forest">
                Is Hierarchical (Categories vs Tags)
            </label>
        </div>

        <div class="field-wrapper">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Schema JSON</label>
            <p class="text-xs text-stone/70 mb-2">Define dynamic fields for Terms in this Taxonomy.</p>
            <textarea name="schema_json" class="field-ui w-full font-mono text-sm" rows="6">{{ old('schema_json', $taxonomy->schema_json ? json_encode($taxonomy->schema_json, JSON_PRETTY_PRINT) : '') }}</textarea>
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-stone/20">
            <a href="{{ route('admin.taxonomies.index') }}" class="btn-outline px-6 py-2 rounded-sm border border-stone/50 text-forest hover:bg-stone/5">Cancel</a>
            <button type="submit" class="btn-solid bg-forest text-white px-8 py-2 rounded-sm text-sm font-bold uppercase tracking-widest">Save Taxonomy</button>
        </div>
    </form>
</div>
@endsection