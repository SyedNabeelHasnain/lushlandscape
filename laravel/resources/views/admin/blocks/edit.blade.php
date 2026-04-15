@extends('admin.layouts.app')
@section('title', 'Page Builder — '.ucwords(str_replace('_', ' ', $pageType)))

@section('content')
@php
    $pageLabel = ucwords(str_replace('_', ' ', $pageType)).($pageId ? ' #'.$pageId : '');
@endphp

<div class="mx-auto max-w-7xl">
    <x-admin.page-header :title="'Page Builder'" :subtitle="'Manage blocks for '.$pageLabel">
        <a href="{{ url()->previous() }}"
            class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-text-secondary transition hover:bg-gray-50 sm:w-auto">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>Back
        </a>
        <button type="submit" form="unified-block-editor-form"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-forest px-4 py-2.5 text-sm font-medium text-white transition hover:bg-forest-light sm:w-auto">
            <i data-lucide="save" class="w-4 h-4"></i>Save Changes
        </button>
    </x-admin.page-header>

    <form id="unified-block-editor-form" method="POST"
        action="{{ route('admin.blocks.update', ['pageType' => $pageType, 'pageId' => $pageId ?? 0]) }}"
        data-ajax-form data-success-message="Blocks saved successfully." class="space-y-6">
        @csrf

        <div class="rounded-3xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
            <x-admin.block-editor :pageType="$pageType" :pageId="$pageId" :blocks="$blocks" :blockTypes="$blockTypes" />
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-forest px-5 py-3 text-sm font-semibold text-white transition hover:bg-forest-light">
                <i data-lucide="save" class="w-4 h-4"></i>Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
