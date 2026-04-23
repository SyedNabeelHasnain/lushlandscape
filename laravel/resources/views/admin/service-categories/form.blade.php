@extends('admin.layouts.app')
@section('title', isset($category) ? 'Edit Category' : 'Create Category')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($category) ? 'Edit: ' . $category->name : 'Create Service Category'" :viewUrl="isset($category) ? url('/services/' . $category->slug) : null" />
<form method="POST" action="{{ isset($category) ? route('admin.service-categories.update', $category) : route('admin.service-categories.store') }}" data-ajax-form="true" data-success-message="{{ isset($category) ? 'Category updated successfully.' : 'Category created.' }}">
    @csrf
    @if(isset($category)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Basic Information">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="name" label="Category Name" :value="$category->name ?? ''" required tooltip="Category name shown in navigation, service hub, and the mega menu header." />
                    <x-admin.form-input name="navigation_label" label="Navigation Label" :value="$category->navigation_label ?? ''" tooltip="Short label for the mega menu if the full category name is too long to display." />
                </div>
                <div class="mt-5">
                    <x-admin.form-select name="parent_id" label="Parent Category" :options="$parents->toArray()" :value="$category->parent_id ?? ''" placeholder="None (Top Level)" tooltip="Optional parent category. Use to create subcategories under a root category. Affects mega menu hierarchy." />
                </div>
                @if(isset($category))
                <div class="mt-5">
                    <x-admin.form-input name="custom_slug" label="Custom Slug" :value="$category->custom_slug ?? ''" help="Leave empty to use auto-generated slug: {{ $category->system_slug }}" tooltip="Override the auto-generated URL slug. Leave blank to use the system-generated slug. Changing this affects SEO and any inbound links." />
                </div>
                @endif
                <div class="mt-5">
                    <x-admin.form-textarea name="short_description" label="Short Description" :value="$category->short_description ?? ''" :rows="3" tooltip="Brief intro for the category hub page and service cards. Shown in search results and category listings." />
                </div>
                <div class="mt-5">
                    <x-admin.form-textarea name="long_description" label="Long Description" :value="$category->long_description ?? ''" :rows="8" tooltip="Full category description rendered on the category landing page. Supports detailed content to improve SEO and visitor understanding." />
                </div>
            </x-admin.card>
            @if(isset($category))
            <x-admin.card title="Content Blocks">
                <p class="text-xs text-text-secondary mb-4">Add rich content sections below the service grid: headings, images, tables, CTAs, and more.</p>
                <x-admin.block-editor
                    pageType="service_category"
                    :pageId="$category->id"
                    :blocks="isset($blocks) ? $blocks->values()->all() : []"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>
            @endif
            <x-admin.card title="SEO & Social">
                <div class="space-y-5">
                    <x-admin.form-input name="meta_title" label="Meta Title" :value="$category->meta_title ?? ''" help="Max 60 characters recommended" tooltip="SEO title for the category page shown in search engine results. Keep under 60 characters." />
                    <x-admin.form-textarea name="meta_description" label="Meta Description" :value="$category->meta_description ?? ''" :rows="2" help="Max 160 characters recommended" tooltip="SEO description for the category page shown in search results. Keep under 160 characters." />
                    <x-admin.form-input name="og_title" label="OG Title" :value="$category->og_title ?? ''" tooltip="Title used when this category page is shared on Facebook, LinkedIn, or iMessage. Can differ from the meta title." />
                    <x-admin.form-textarea name="og_description" label="OG Description" :value="$category->og_description ?? ''" :rows="2" tooltip="Description shown in social media link previews when this category page is shared. Keep under 200 characters." />
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-select name="status" label="Status" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" :value="$category->status ?? 'draft'" required tooltip="Published = visible in navigation and on the frontend. Draft = hidden from public view." />
                    <x-admin.form-input name="sort_order" label="Sort Order" type="number" :value="$category->sort_order ?? 0" tooltip="Display order in navigation and on the services hub page. Lower numbers appear first." />
                    <x-admin.form-input name="icon" label="Icon (Lucide name)" :value="$category->icon ?? ''" help="e.g. layers, wrench, hammer" tooltip="Lucide icon name used in the mega menu and admin sidebar. Browse icons at lucide.dev. Example: leaf, hammer, tree." />
                </div>
            </x-admin.card>
            <x-admin.card title="Category Image">
                <x-admin.form-media name="hero_media_id" label="Category Photo" :mediaAsset="$category->heroMedia ?? null" help="Hero/thumbnail image for this category on the frontend." tooltip="Shown on the category hub page and in service grid cards. Landscape, min 1200×600px recommended." :croppable="true" />
            </x-admin.card>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($category) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.service-categories.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
