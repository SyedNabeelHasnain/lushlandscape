@extends('admin.layouts.app')
@section('title', 'FAQs')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="FAQ Bank" subtitle="Centralized FAQ management" :createRoute="route('admin.faqs.create')" createLabel="Add FAQ">
    <x-admin.import-export-buttons table="faqs" />
</x-admin.page-header>
<div class="mb-6 flex flex-wrap gap-3">
    <form class="flex flex-wrap gap-3" method="GET" x-data="{}">
        <label for="faq-category" class="sr-only">Filter by category</label>
        <select id="faq-category" name="category" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white" x-on:change="$el.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $id => $name)<option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>{{ $name }}</option>@endforeach
        </select>
        <label for="faq-search" class="sr-only">Search FAQs</label>
        <input type="text" id="faq-search" name="search" value="{{ request('search') }}" placeholder="Search..." class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
        <button type="submit" class="px-4 py-2 bg-forest text-white rounded-xl text-sm">Filter</button>
    </form>
</div>
<x-admin.data-table :headers="['Question', 'Category', 'Type', 'Status']">
    @forelse($faqs as $faq)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text max-w-md truncate">{{ $faq->question }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $faq->category->name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $faq->faq_type }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$faq->status" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.faqs.edit', $faq) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.faqs.destroy', $faq)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-text-secondary">No FAQs yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $faqs->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
