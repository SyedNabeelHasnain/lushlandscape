@extends('admin.layouts.app')
@section('title', isset($layout) ? 'Edit Layout' : 'Create Layout')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($layout) ? 'Edit Layout: ' . $layout->name : 'Create Theme Layout'" />
<form method="POST" action="{{ isset($layout) ? route('admin.theme-layouts.update', $layout) : route('admin.theme-layouts.store') }}" data-ajax-form="true" data-success-message="{{ isset($layout) ? 'Layout updated successfully.' : 'Layout created.' }}">
    @csrf
    @if(isset($layout)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 space-y-6">
            <x-admin.card title="Layout Details">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="name" label="Layout Name" :value="$layout->name ?? ''" required tooltip="E.g., 'Main Site Header' or 'Dark Footer'." />
                    <div>
                        <label class="block text-sm font-semibold text-text mb-2">Layout Type <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full px-4 py-3 bg-stone-light/50 border border-stone rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest transition" required>
                            <option value="">Select Type...</option>
                            <option value="header" {{ (old('type', $layout->type ?? '')) == 'header' ? 'selected' : '' }}>Header</option>
                            <option value="footer" {{ (old('type', $layout->type ?? '')) == 'footer' ? 'selected' : '' }}>Footer</option>
                            <option value="single" {{ (old('type', $layout->type ?? '')) == 'single' ? 'selected' : '' }}>Single Post/Service (Future)</option>
                            <option value="archive" {{ (old('type', $layout->type ?? '')) == 'archive' ? 'selected' : '' }}>Archive (Future)</option>
                        </select>
                    </div>
                </div>
            </x-admin.card>

            @if(isset($layout))
            <x-admin.card title="Theme Builder" class="mt-6">
                <p class="text-xs text-text-secondary mb-4">Design the structure of this {{ ucfirst($layout->type) }}.</p>
                <div class="bg-blue-50 p-4 mb-4 rounded-lg flex items-start gap-3 border border-blue-100">
                    <i data-lucide="info" class="w-5 h-5 text-blue-500 mt-0.5"></i>
                    <div class="text-xs text-text-secondary">
                        <strong class="text-text block mb-1">Building a {{ ucfirst($layout->type) }}:</strong>
                        @if($layout->type === 'header')
                        Use layout containers to structure your header. You can drop a <strong>Site Logo</strong> block and a <strong>Navigation Menu</strong> block to create a standard header.
                        @elseif($layout->type === 'footer')
                        Use grid columns to build your footer. Use text blocks for links and copyright info.
                        @else
                        This layout type is reserved for future complex content routing.
                        @endif
                    </div>
                </div>
                {{-- Make sure pageType matches what we use in ThemeLayoutController: "theme_layout" --}}
                <x-admin.block-editor
                    pageType="theme_layout"
                    :pageId="$layout->id"
                    :blocks="$blocks ?? collect()"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>
            <x-admin.content-block-export type="theme_layout" :id="$layout->id" />
            @else
            <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-sm mt-6 flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 mt-0.5 shrink-0 text-amber-500"></i>
                <p>Create the layout name and type first. Once saved, you'll be able to design the actual visual layout using the block editor.</p>
            </div>
            @endif
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-toggle name="is_active" label="Set as Active" :checked="$layout->is_active ?? true" help="If active, this layout will forcibly override the default hardcoded layout of this type." />
                </div>
            </x-admin.card>
            
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($layout) ? 'Update' : 'Create Layout' }}</button>
                <a href="{{ route('admin.theme-layouts.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
