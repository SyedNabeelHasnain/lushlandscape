@extends('admin.layouts.app')
@section('title', 'Service-City Pages')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Service-City Pages" subtitle="Manage local service landing pages">
    <x-admin.import-export-buttons table="service_city_pages" />
    <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
        <a href="{{ route('admin.service-city-pages.create') }}" class="inline-flex items-center justify-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-4 py-2.5 rounded-xl transition text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>Create Page
        </a>
        <form method="POST" action="{{ route('admin.service-city-pages.generate') }}" class="flex flex-col gap-2 sm:inline-flex sm:flex-row">
            @csrf
            <select name="city_id" required class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white min-w-0">
                <option value="">Generate for city...</option>
                @foreach($cities as $id => $name)<option value="{{ $id }}">{{ $name }}</option>@endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-forest-light hover:bg-forest text-white rounded-xl text-sm">Generate All</button>
        </form>
    </div>
</x-admin.page-header>
<div class="mb-6 flex flex-wrap gap-3">
    <form class="flex flex-wrap gap-3" method="GET" x-data="{}">
        <select name="city" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white" x-on:change="$el.form.submit()">
            <option value="">All Cities</option>
            @foreach($cities as $id => $name)<option value="{{ $id }}" {{ request('city') == $id ? 'selected' : '' }}>{{ $name }}</option>@endforeach
        </select>
        <select name="service" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white" x-on:change="$el.form.submit()">
            <option value="">All Services</option>
            @foreach($services as $id => $name)<option value="{{ $id }}" {{ request('service') == $id ? 'selected' : '' }}>{{ $name }}</option>@endforeach
        </select>
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white" x-on:change="$el.form.submit()">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </form>
</div>
<x-admin.data-table :headers="['Service', 'City', 'Slug', 'Active', 'Indexable']">
    @forelse($pages as $pg)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $pg->service->name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $pg->city->name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary font-mono text-xs">{{ $pg->slug_final }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$pg->is_active ? 'active' : 'inactive'" /></td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $pg->is_indexable ? 'Yes' : 'No' }}</td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.service-city-pages.edit', $pg) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.service-city-pages.destroy', $pg)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-text-secondary">No service-city pages yet. Use "Generate All" to create pages for a city.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $pages->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
