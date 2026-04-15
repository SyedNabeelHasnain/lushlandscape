@extends('admin.layouts.app')
@section('title', $config['title'])
@section('content')

<x-admin.flash-message />
<x-admin.page-header :title="$config['title']" :viewUrl="url($config['preview_path'])">
    <x-admin.import-export-buttons table="page_blocks" />
</x-admin.page-header>

<form method="POST" action="{{ route('admin.page-builders.update', ['page' => $config['key']]) }}" data-ajax-form="true"
    data-success-message="{{ $config['success_message'] }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Page Layout & Content">
                <p class="text-xs text-text-secondary mb-6 italic">
                    {{ $config['helper'] }}
                </p>

                <x-admin.block-editor
                    :pageType="$pageType"
                    :pageId="$pageId"
                    :blocks="$blocks->toArray()"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>

            <x-admin.content-block-export :type="$pageType" :id="$pageId" />
        </div>

        <div class="space-y-4">
            <x-admin.card title="Quick Reference">
                <div class="space-y-3 text-sm text-gray-500">
                    <div class="p-3 bg-forest-50 rounded-xl border border-forest/10">
                        <p class="font-semibold text-forest text-xs mb-1">Block-First Surface</p>
                        <p class="text-xs">This page is now fully block-driven. Use the builder plus page JSON export/import to move complete page feeds between environments.</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <p class="font-semibold text-blue-700 text-xs mb-1">Preview Carefully</p>
                        <p class="text-xs text-blue-600">Use the preview link after each save to confirm spacing, tone, and data-driven sections before publishing changes broadly.</p>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card>
                <button type="submit" data-loading-label="Saving…"
                    class="w-full bg-forest hover:bg-forest-dark text-white font-semibold py-3 px-6 rounded-xl transition text-sm flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save {{ $config['label'] }}
                </button>
                <a href="{{ url($config['preview_path']) }}" target="_blank"
                    class="mt-3 block text-center text-xs text-text-secondary hover:text-forest transition">
                    Preview →
                </a>
            </x-admin.card>
        </div>
    </div>
</form>

@endsection
