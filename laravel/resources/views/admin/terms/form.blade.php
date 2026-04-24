@extends('admin.layouts.app')

@section('title', ($term->exists ? 'Edit' : 'Create') . ' Term | ' . $taxonomy->name)
@section('header', ($term->exists ? 'Edit' : 'Create') . ' Term in ' . $taxonomy->name)

@section('content')
<div class="card p-8">
    <div class="mb-6">
        <a href="{{ route('admin.taxonomies.terms.index', $taxonomy) }}" class="text-xs font-semibold text-stone/50 hover:text-forest uppercase tracking-widest">&larr; Back to {{ $taxonomy->name }}</a>
    </div>

    <form action="{{ $term->exists ? route('admin.taxonomies.terms.update', [$taxonomy, $term]) : route('admin.taxonomies.terms.store', $taxonomy) }}" method="POST" class="space-y-6 max-w-4xl">
        @csrf
        @if($term->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $term->name) }}" class="field-ui w-full" required>
            </div>
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Slug *</label>
                <input type="text" name="slug" value="{{ old('slug', $term->slug) }}" class="field-ui w-full" required>
            </div>
        </div>

        @if($taxonomy->is_hierarchical)
        <div class="field-wrapper max-w-md">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Parent Term</label>
            <select name="parent_id" class="field-ui w-full">
                <option value="">None</option>
                @foreach($parents as $id => $name)
                    <option value="{{ $id }}" {{ old('parent_id', $term->parent_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div class="field-wrapper">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Description</label>
            <textarea name="description" class="field-ui w-full" rows="3">{{ old('description', $term->description) }}</textarea>
        </div>

        {{-- Dynamic Schema Fields --}}
        @if(!empty($taxonomy->schema_json))
            <div class="mt-8 pt-8 border-t border-gray-100">
                <h3 class="text-sm font-bold tracking-widest text-forest mb-6 uppercase">Dynamic Content</h3>
                <div class="space-y-6">
                    @foreach($taxonomy->schema_json as $field)
                        @php
                            $fieldName = 'data[' . $field['name'] . ']';
                            $fieldValue = old('data.'.$field['name'], $term->data ? ($term->data[$field['name']] ?? '') : '');
                        @endphp

                        @if($field['type'] === 'text')
                            <x-admin.form-input :name="$fieldName" :label="$field['label']" :value="$fieldValue" :help="$field['help'] ?? ''" />
                        @elseif($field['type'] === 'textarea')
                            <x-admin.form-textarea :name="$fieldName" :label="$field['label']" :value="$fieldValue" :help="$field['help'] ?? ''" rows="4" />
                        @elseif($field['type'] === 'richtext')
                            <x-admin.rich-editor :name="$fieldName" :label="$field['label']" :value="$fieldValue" :help="$field['help'] ?? ''" />
                        @elseif($field['type'] === 'image')
                            @php
                                $mediaAsset = null;
                                if ($fieldValue) {
                                    $mediaAsset = \App\Models\MediaAsset::find($fieldValue);
                                }
                            @endphp
                            <x-admin.media-picker :name="$fieldName" :label="$field['label']" :mediaAsset="$mediaAsset" :help="$field['help'] ?? ''" />
                        @elseif($field['type'] === 'toggle')
                            <x-admin.form-toggle :name="$fieldName" :label="$field['label']" :checked="(bool)$fieldValue" :help="$field['help'] ?? ''" />
                        @else
                            <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg text-sm text-orange-800">
                                Unsupported field type: {{ $field['type'] }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @else
            <div class="field-wrapper mt-8 pt-8 border-t border-gray-100">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Dynamic Data (JSON)</label>
                <p class="text-xs text-stone/70 mb-2">No schema defined on the Taxonomy. Edit JSON manually.</p>
                <textarea name="data_json" class="field-ui w-full font-mono text-sm bg-gray-50" rows="10">{{ old('data_json', $term->data ? json_encode($term->data, JSON_PRETTY_PRINT) : '{}') }}</textarea>
            </div>
        @endif

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-stone/20">
            <a href="{{ route('admin.taxonomies.terms.index', $taxonomy) }}" class="btn-outline px-6 py-2 rounded-sm border border-stone/50 text-forest hover:bg-stone/5">Cancel</a>
            <button type="submit" class="btn-solid bg-forest text-white px-8 py-2 rounded-sm text-sm font-bold uppercase tracking-widest">Save Term</button>
        </div>
    </form>
</div>
@endsection