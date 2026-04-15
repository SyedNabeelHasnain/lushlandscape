@extends('admin.layouts.app')
@section('title', 'Blog Categories')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Blog Categories" :createRoute="route('admin.blog-categories.create')" createLabel="Add Category" />
<x-admin.data-table :headers="['Name', 'Slug', 'Posts', 'Status']">
    @forelse($categories as $cat)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $cat->name }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $cat->slug }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $cat->posts_count }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$cat->status" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.blog-categories.edit', $cat) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.blog-categories.destroy', $cat)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-text-secondary">No blog categories yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $categories->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
