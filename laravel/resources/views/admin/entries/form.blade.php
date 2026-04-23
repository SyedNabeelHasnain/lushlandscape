@extends('admin.layouts.app')

@section('title', ($entry->exists ?? false ? 'Edit' : 'Create') . ' Entry | Super WMS')
@section('header', ($entry->exists ?? false ? 'Edit' : 'Create') . ' ' . $contentType->name)

@section('content')
<div class="card p-8">
    <form action="{{ $entry->exists ?? false ? route('admin.entries.update', $entry) : route('admin.entries.store') }}" method="POST" class="space-y-6">
        @csrf
        @if($entry->exists ?? false) @method('PUT') @endif
        <input type="hidden" name="content_type_id" value="{{ $contentType->id }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Core Fields & Data JSON --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="field-wrapper">
                        <label class="block text-sm font-bold tracking-widest text-forest mb-2">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $entry->title ?? '') }}" class="field-ui w-full" required>
                    </div>
                    <div class="field-wrapper">
                        <label class="block text-sm font-bold tracking-widest text-forest mb-2">Slug *</label>
                        <input type="text" name="slug" value="{{ old('slug', $entry->slug ?? '') }}" class="field-ui w-full" required>
                    </div>
                </div>

                @if($contentType->is_hierarchical)
                <div class="field-wrapper">
                    <label class="block text-sm font-bold tracking-widest text-forest mb-2">Parent Entry</label>
                    <select name="parent_id" class="field-ui w-full">
                        <option value="">None</option>
                        @foreach($parents as $id => $name)
                            <option value="{{ $id }}" {{ old('parent_id', $entry->parent_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="field-wrapper">
                    <label class="block text-sm font-bold tracking-widest text-forest mb-2">Dynamic Data (JSON) *</label>
                    <p class="text-xs text-stone/70 mb-2">Edit custom fields directly. In a future update, this will render dynamic UI fields based on ContentType schema.</p>
                    <textarea name="data_json" class="field-ui w-full font-mono text-sm" rows="15">{{ old('data_json', isset($entry) && $entry->data ? json_encode($entry->data, JSON_PRETTY_PRINT) : '{}') }}</textarea>
                </div>

                <div class="field-wrapper">
                    <label class="block text-sm font-bold tracking-widest text-forest mb-2">Page Blocks (JSON)</label>
                    <p class="text-xs text-stone/70 mb-2">Attach blocks using the JSON API.</p>
                    <textarea name="blocks_json" class="field-ui w-full font-mono text-sm" rows="5">[]</textarea>
                </div>
            </div>

            {{-- Right Column: Meta & Status --}}
            <div class="space-y-6">
                <div class="card bg-stone/5 p-6 border border-stone/20">
                    <h3 class="text-sm font-bold tracking-widest uppercase text-forest mb-4">Publishing</h3>
                    <div class="field-wrapper mb-4">
                        <label class="block text-xs font-semibold text-stone/70 mb-1">Status</label>
                        <select name="status" class="field-ui w-full text-sm">
                            <option value="draft" {{ old('status', $entry->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $entry->status ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $entry->status ?? '') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="field-wrapper mb-6">
                        <label class="block text-xs font-semibold text-stone/70 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $entry->sort_order ?? 0) }}" class="field-ui w-full text-sm">
                    </div>
                    <button type="submit" class="btn-solid bg-forest text-white w-full py-3 rounded-sm text-xs font-bold uppercase tracking-widest">Save Entry</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
