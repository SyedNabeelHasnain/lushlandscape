@extends('admin.layouts.app')
@section('title', 'Redirects')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="URL Redirects" :createRoute="route('admin.redirects.create')" createLabel="Add Redirect">
    <x-admin.import-export-buttons table="redirects" />
</x-admin.page-header>
<x-admin.data-table :headers="['Old URL', 'New URL', 'Code', 'Active']">
    @forelse($redirects as $r)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-mono text-text text-xs">{{ $r->old_url }}</td>
        <td class="px-6 py-4 text-sm font-mono text-text-secondary text-xs">{{ $r->new_url }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $r->status_code }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$r->is_active ? 'active' : 'inactive'" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.redirects.edit', $r) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.redirects.destroy', $r)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-text-secondary">No redirects yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $redirects->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
