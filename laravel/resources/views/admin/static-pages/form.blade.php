@extends('admin.layouts.app')
@section('title', isset($page) ? 'Edit Page' : 'Create Page')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($page) ? 'Edit: ' . $page->title : 'Create Page'" :viewUrl="isset($page) ? url('/' . $page->slug) : null" />
<form method="POST" action="{{ isset($page) ? route('admin.static-pages.update', $page) : route('admin.static-pages.store') }}" data-ajax-form="true" data-success-message="{{ isset($page) ? 'Page updated successfully.' : 'Page created.' }}">
    @csrf
    @if(isset($page)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Page Content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="title" label="Title" :value="$page->title ?? ''" required />
                    <x-admin.form-input name="slug" label="Slug" :value="$page->slug ?? ''" required />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-admin.form-input name="page_type" label="Page Type" :value="$page->page_type ?? 'standard'" />
                    <x-admin.form-input name="template" label="Template" :value="$page->template ?? 'default'" />
                </div>
                <div class="mt-5"><x-admin.form-textarea name="excerpt" label="Excerpt" :value="$page->excerpt ?? ''" :rows="3" /></div>
                <div class="mt-5"><x-admin.rich-editor name="body" label="Body Content" :value="$page->body ?? ''" /></div>
            </x-admin.card>

            @if(isset($page))
            <x-admin.card title="Content Blocks" class="mt-6">
                <p class="text-xs text-text-secondary mb-4">Build structured content below the body. Blocks appear in order on the frontend.</p>
                <x-admin.block-editor
                    pageType="static_page"
                    :pageId="$page->id"
                    :blocks="$blocks ?? collect()"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>
            <x-admin.content-block-export type="static_page" :id="$page->id" />
            @endif
            <x-admin.card title="SEO & Social">
                <div class="space-y-5">
                    <x-admin.form-input name="meta_title" label="Meta Title" :value="$page->meta_title ?? ''" />
                    <x-admin.form-textarea name="meta_description" label="Meta Description" :value="$page->meta_description ?? ''" :rows="2" />
                    <x-admin.form-input name="og_title" label="OG Title" :value="$page->og_title ?? ''" />
                    <x-admin.form-textarea name="og_description" label="OG Description" :value="$page->og_description ?? ''" :rows="2" />
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-select name="status" label="Status" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" :value="$page->status ?? 'draft'" required />
                    <x-admin.form-toggle name="is_indexable" label="Indexable" :checked="$page->is_indexable ?? true" />
                    <x-admin.form-input name="sort_order" label="Sort Order" type="number" :value="$page->sort_order ?? 0" />
                </div>
            </x-admin.card>
            <x-admin.card title="Hero Image">
                <x-admin.form-media
                    name="hero_media_id"
                    label="Hero Image"
                    :mediaAsset="$page->heroMedia ?? null"
                    :croppable="true" />
            </x-admin.card>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($page) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.static-pages.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
