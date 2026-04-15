@extends('admin.layouts.app')
@section('title', 'Static Pages')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Static Pages" subtitle="About, Contact, Legal, and other pages" :createRoute="route('admin.static-pages.create')" createLabel="Add Page">
    <x-admin.import-export-buttons table="static_pages" />
</x-admin.page-header>
<x-admin.data-table :headers="['Title', 'Slug', 'Type', 'Status']">
    @forelse($pages as $pg)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $pg->title }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $pg->slug }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $pg->page_type }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$pg->status" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.static-pages.edit', $pg) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.static-pages.destroy', $pg)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-text-secondary">No pages yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $pages->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
