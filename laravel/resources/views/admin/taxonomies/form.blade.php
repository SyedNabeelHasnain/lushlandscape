@extends('admin.layouts.app')
@section('title', isset($item) ? 'Edit ' . $cfg['singular'] : 'Create ' . $cfg['singular'])
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($item) ? 'Edit: ' . $item->name : 'New ' . $cfg['singular']" :viewUrl="$viewUrl ?? null" />

@php
    $actionUrl = isset($item)
        ? route('admin.' . $cfg['key'] . '.update', $item)
        : route('admin.' . $cfg['key'] . '.store');
    $currentMedia = isset($item) ? $item->image : null;
@endphp

<form method="POST" action="{{ $actionUrl }}" data-ajax-form="true">
    @csrf
    @if(isset($item)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Basic Info">
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5" x-data="{ slugLocked: {{ isset($item) ? 'true' : 'false' }} }">
                        <div>
                            <label class="block text-sm font-medium text-text mb-1.5">Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" required
                                x-on:input="if (!slugLocked) { $refs.slugField.value = $event.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') }"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm @error('name') border-red-300 @enderror">
                            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-text mb-1.5">Slug <span class="text-red-500">*</span></label>
                            <input type="text" name="slug" x-ref="slugField" value="{{ old('slug', $item->slug ?? '') }}" required
                                x-on:input="slugLocked = true"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm font-mono @error('slug') border-red-300 @enderror">
                            <p class="text-xs text-text-secondary mt-1">Auto-filled from name. Edit to override.</p>
                            @error('slug')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <x-admin.form-select
                        name="parent_id"
                        label="Parent Category"
                        :options="$parents->toArray()"
                        :value="old('parent_id', $item->parent_id ?? '')"
                        placeholder="- None (top-level) -"
                    />
                    @if($cfg['has_icon'])
                    <x-admin.form-input name="icon" label="Icon (Lucide name)" :value="old('icon', $item->icon ?? '')" help="e.g. leaf, tree-pine, flower" />
                    @endif
                    <x-admin.form-textarea name="short_description" label="Short Description" :value="old('short_description', $item->short_description ?? '')" :rows="2" help="Used in cards and previews (max 500 chars)" />
                    <x-admin.form-textarea name="description" label="Full Description" :value="old('description', $item->description ?? '')" :rows="5" />
                </div>
            </x-admin.card>

            <x-admin.card title="Featured Image">
                <x-admin.media-picker name="image_media_id" label="Category Image" :mediaAsset="$currentMedia" :croppable="true" />
            </x-admin.card>

            <x-admin.card title="Open Graph">
                <div class="space-y-5">
                    <x-admin.form-input name="og_title" label="OG Title" :value="old('og_title', $item->og_title ?? '')" help="Defaults to Name if empty" />
                    <x-admin.form-textarea name="og_description" label="OG Description" :value="old('og_description', $item->og_description ?? '')" :rows="2" />
                </div>
            </x-admin.card>

            @if(request()->routeIs('admin.service-categories.*'))
            <x-admin.card title="Target Keywords">
                <div class="space-y-4">
                    <p class="text-xs text-text-secondary">Define target keywords. Used to guide AI content generation and SEO.</p>
                    @php $kw = $item->keywords_json ?? []; @endphp
                    <x-admin.form-input name="keywords_primary" label="Primary Keywords" :value="implode(', ', $kw['primary'] ?? [])" help="Comma-separated." />
                    <x-admin.form-input name="keywords_secondary" label="Secondary Keywords" :value="implode(', ', $kw['secondary'] ?? [])" help="Comma-separated." />
                    <x-admin.form-input name="keywords_long_tail" label="Long-Tail Keywords" :value="implode(', ', $kw['long_tail'] ?? [])" help="Comma-separated." />
                </div>
            </x-admin.card>
            @endif

            @if(isset($item) && ($supportsPageBuilder ?? false))
            <x-admin.card title="Content Blocks">
                <p class="text-xs text-text-secondary mb-4">Add custom content blocks to this category page: descriptions, images, feature lists, CTAs, and more.</p>
                @php
                    $existingBlocks = isset($blocks) ? $blocks->values()->all() : [];
                @endphp
                <x-admin.block-editor
                    :pageType="$pageType"
                    :pageId="$item->id"
                    :blocks="$existingBlocks"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>
            @elseif(isset($item))
            <x-admin.card title="Frontend Availability">
                <p class="text-sm text-text-secondary">This taxonomy is currently used for CMS organization only. It does not have a dedicated public-facing landing page, so block-builder content is intentionally disabled here.</p>
            </x-admin.card>
            @endif

            <x-admin.card title="SEO">
                <div class="space-y-5">
                    <x-admin.form-input name="meta_title" label="Meta Title" :value="old('meta_title', $item->meta_title ?? '')" help="50–60 characters recommended" />
                    <x-admin.form-textarea name="meta_description" label="Meta Description" :value="old('meta_description', $item->meta_description ?? '')" :rows="2" help="150–160 characters recommended" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="schema_type" label="Schema Type" :value="old('schema_type', $item->schema_type ?? $cfg['schema_default'])" help="e.g. CollectionPage, FAQPage, ItemList" />
                        @if($cfg['has_language'])
                        <x-admin.form-select name="language" label="Language" :options="['en' => 'English', 'fr' => 'French']" :value="old('language', $item->language ?? 'en')" />
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Schema JSON (override)</label>
                        <textarea name="schema_json" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm font-mono @error('schema_json') border-red-300 @enderror">{{ old('schema_json', isset($item->schema_json) ? json_encode($item->schema_json, JSON_PRETTY_PRINT) : '') }}</textarea>
                        @error('schema_json')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-select
                        name="status"
                        label="Status"
                        :options="['published' => 'Published', 'draft' => 'Draft']"
                        :value="old('status', $item->status ?? 'draft')"
                        required
                    />
                    <x-admin.form-input name="sort_order" label="Sort Order" type="number" :value="old('sort_order', $item->sort_order ?? 0)" />
                </div>
            </x-admin.card>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">
                    {{ isset($item) ? 'Update' : 'Create' }}
                </button>
                <a href="{{ route('admin.' . $cfg['key'] . '.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
