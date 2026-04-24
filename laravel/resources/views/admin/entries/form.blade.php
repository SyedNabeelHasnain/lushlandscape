@extends('admin.layouts.app')

@section('title', ($entry->exists ?? false ? 'Edit' : 'Create') . ' ' . $contentType->name . ' | Super WMS')
@section('header', ($entry->exists ?? false ? 'Edit' : 'Create') . ' ' . $contentType->name)

@section('content')
<div class="card p-8" x-data="{
    schema: {{ json_encode($contentType->schema_json ?? []) }},
    data: {{ json_encode(old('data', isset($entry) && $entry->data ? $entry->data : [])) }}
}">
    <form action="{{ $entry->exists ?? false ? route('admin.entries.update', $entry) : route('admin.entries.store') }}" method="POST" class="space-y-6">
        @csrf
        @if($entry->exists ?? false) @method('PUT') @endif
        <input type="hidden" name="content_type_id" value="{{ $contentType->id }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Core Fields & Dynamic Data --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-admin.form-input name="title" label="Title" :value="old('title', $entry->title ?? '')" required />
                    <x-admin.form-input name="slug" label="Slug" :value="old('slug', $entry->slug ?? '')" required help="URL friendly path segment." />
                </div>

                @if($contentType->is_hierarchical)
                <x-admin.form-select name="parent_id" label="Parent Entry" :options="$parents" :value="old('parent_id', $entry->parent_id ?? '')" placeholder="None" />
                @endif

                {{-- Dynamic UI Form Generator --}}
                @if(!empty($contentType->schema_json))
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <h3 class="text-sm font-bold tracking-widest text-forest mb-6 uppercase">Dynamic Content</h3>
                        <div class="space-y-6">
                            @foreach($contentType->schema_json as $field)
                                @php
                                    $fieldName = 'data[' . $field['name'] . ']';
                                    $fieldValue = old('data.'.$field['name'], isset($entry) && $entry->data ? ($entry->data[$field['name']] ?? '') : '');
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
                                        Unsupported field type: {{ $field['type'] }} for field {{ $field['name'] }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    {{-- Fallback JSON Editor if no schema is defined --}}
                    <div class="field-wrapper mt-8 pt-8 border-t border-gray-100">
                        <label class="block text-sm font-bold tracking-widest text-forest mb-2">Dynamic Data (JSON) *</label>
                        <p class="text-xs text-stone/70 mb-2">No schema defined for this Content Type. Edit JSON manually.</p>
                        <textarea name="data_json" class="field-ui w-full font-mono text-sm bg-gray-50" rows="15">{{ old('data_json', isset($entry) && $entry->data ? json_encode($entry->data, JSON_PRETTY_PRINT) : '{}') }}</textarea>
                    </div>
                @endif

                <div class="mt-8 pt-8 border-t border-gray-100">
                    <label class="block text-sm font-bold tracking-widest text-forest mb-2">Page Blocks</label>
                    <p class="text-xs text-stone/70 mb-4">Design the page layout using the Visual Block Editor.</p>
                    <x-admin.block-editor 
                        :blocks="$blocks ?? []" 
                        :blockTypes="$blockTypes ?? []" 
                        inputName="blocks_json" 
                    />
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
                    <button type="submit" class="btn-solid bg-forest text-white w-full py-3 rounded-sm text-xs font-bold uppercase tracking-widest">
                        {{ $entry->exists ?? false ? 'Update Entry' : 'Publish Entry' }}
                    </button>
                </div>

                @if(!empty($taxonomies))
                    <div class="card bg-stone/5 p-6 border border-stone/20">
                        <h3 class="text-sm font-bold tracking-widest uppercase text-forest mb-4">Taxonomies</h3>
                        @foreach($taxonomies as $taxonomy)
                            <div class="field-wrapper mb-4">
                                <label class="block text-xs font-semibold text-stone/70 mb-1">{{ $taxonomy->name }}</label>
                                <select name="terms[]" class="field-ui w-full text-sm" multiple size="4">
                                    @foreach($taxonomy->terms as $term)
                                        <option value="{{ $term->id }}" 
                                            {{ in_array($term->id, old('terms', isset($entry) ? $entry->terms->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                            {{ $term->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
