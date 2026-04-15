@extends('admin.layouts.app')
@section('title', 'Portfolio')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Portfolio Projects" :createRoute="route('admin.portfolio.create')" createLabel="Add Project">
    <x-admin.import-export-buttons table="portfolio_projects" />
</x-admin.page-header>
<x-admin.data-table :headers="['Title', 'City', 'Service', 'Status']">
    @forelse($projects as $proj)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $proj->title }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $proj->city->name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $proj->service->name ?? '-' }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$proj->status" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.portfolio.edit', $proj) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.portfolio.destroy', $proj)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-text-secondary">No portfolio projects yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $projects->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
