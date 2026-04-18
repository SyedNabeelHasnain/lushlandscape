@extends('admin.layouts.app')
@section('title', 'Home Page Builder')
@section('content')

<x-admin.flash-message />
<x-admin.page-header title="Home Page Builder" :viewUrl="route('home')">
    <x-admin.import-export-buttons table="page_blocks" />
</x-admin.page-header>

<form method="POST" action="{{ route('admin.home-page.update') }}" data-ajax-form="true" data-success-message="Home page settings saved.">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Unified Page Builder --}}
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Page Layout & Content">
                <p class="text-xs text-text-secondary mb-6 italic">
                    Drag and drop any element to reorder. Use the <i data-lucide="eye" class="w-3 h-3 inline"></i> toggle to enable/disable, 
                    <i data-lucide="monitor" class="w-3 h-3 inline"></i> / <i data-lucide="smartphone" class="w-3 h-3 inline"></i> to control device visibility, 
                    and <i data-lucide="settings-2" class="w-3 h-3 inline"></i> to edit settings.
                </p>
                
                <x-admin.block-editor
                    pageType="home"
                    :pageId="0"
                    :blocks="$blocks->toArray()"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>

            <x-admin.card title="Home Page SEO & Social">
                <div class="space-y-5">
                    <x-admin.form-input name="seo_home_title" label="Meta Title" :value="\App\Models\Setting::get('seo_home_title', '')" />
                    <x-admin.form-textarea name="seo_home_description" label="Meta Description" :value="\App\Models\Setting::get('seo_home_description', '')" :rows="2" />
                    <x-admin.form-input name="seo_home_og_title" label="OG Title" :value="\App\Models\Setting::get('seo_home_og_title', '')" />
                    <x-admin.form-textarea name="seo_home_og_description" label="OG Description" :value="\App\Models\Setting::get('seo_home_og_description', '')" :rows="2" />
                    <x-admin.form-media
                        name="seo_home_og_image_id"
                        label="OG Image (Social Share Image)"
                        :mediaAsset="\App\Models\MediaAsset::find(\App\Models\Setting::get('seo_home_og_image_id'))"
                    />
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <x-admin.card title="Quick Reference">
                <div class="space-y-3 text-sm text-gray-500">
                    <div class="p-3 bg-forest-50 rounded-xl border border-forest/10">
                        <p class="font-semibold text-forest text-xs mb-1">Section Settings</p>
                        <p class="text-xs">Expand each section row to configure its heading, content limit, and other options. Changes are saved when you click "Save Home Page".</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                        <p class="font-semibold text-amber-700 text-xs mb-1">Dynamic Content</p>
                        <p class="text-xs text-amber-600">Services, portfolio, reviews, cities, and blog posts are pulled automatically from your CMS. No manual entry needed.</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <p class="font-semibold text-blue-700 text-xs mb-1">Hero Texts & CTAs</p>
                        <p class="text-xs text-blue-600">Primary CTA text and URL fall back to Settings → Trust if left blank.</p>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card>
                <button type="submit" data-loading-label="Saving…"
                        class="w-full bg-forest hover:bg-forest-dark text-white font-semibold py-3 px-6 rounded-xl transition text-sm flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Home Page
                </button>
                <a href="{{ route('home') }}" target="_blank"
                   class="mt-3 block text-center text-xs text-text-secondary hover:text-forest transition">
                    Preview →
                </a>
            </x-admin.card>

            {{-- Tips card --}}
            <x-admin.card title="How It Works">
                <div class="space-y-2 text-xs text-gray-500">
                    <p><span class="font-semibold text-gray-700">Toggle</span>: enable or disable any section from the section manager on the left.</p>
                    <p><span class="font-semibold text-gray-700">Reorder</span>: drag sections up or down to change display order on the homepage.</p>
                    <p><span class="font-semibold text-gray-700">Expand</span>: click a section row to edit its heading, content limit, and settings.</p>
                    <p><span class="font-semibold text-gray-700">Save</span>: click "Save Home Page" to persist all changes.</p>
                </div>
            </x-admin.card>
        </div>

    </div>
</form>

@endsection
