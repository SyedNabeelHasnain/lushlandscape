@extends('admin.layouts.app')
@section('title', 'Cities')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Cities" subtitle="Manage service area cities" :createRoute="route('admin.cities.create')" createLabel="Add City">
    <x-admin.import-export-buttons table="cities" />
</x-admin.page-header>
<x-admin.data-table :headers="['Name', 'Region', 'Slug', 'Pages (Active/Total)', 'Status']">
    @forelse($cities as $city)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $city->name }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $city->region_name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $city->slug_final }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $city->active_service_pages_count }} / {{ $city->service_pages_count }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$city->status" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.cities.edit', $city) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.cities.destroy', $city)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-text-secondary">No cities yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $cities->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
