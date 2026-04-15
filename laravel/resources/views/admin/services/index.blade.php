@extends('admin.layouts.app')
@section('title', 'Services')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Services" subtitle="Manage individual service pages" :createRoute="route('admin.services.create')" createLabel="Add Service">
    <x-admin.import-export-buttons table="services" />
</x-admin.page-header>
<div class="mb-6 flex flex-wrap gap-3">
    <form class="flex flex-wrap gap-3" method="GET" x-data="{}">
        <select name="category" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white" x-on:change="$el.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $id => $name)<option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>{{ $name }}</option>@endforeach
        </select>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search services..." class="px-3 py-2 border border-gray-200 rounded-xl text-sm">
        <button type="submit" class="px-4 py-2 bg-forest text-white rounded-xl text-sm">Filter</button>
    </form>
</div>
<x-admin.data-table :headers="['Name', 'Category', 'Cities', 'Status', 'Order']">
    @forelse($services as $svc)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $svc->name }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $svc->category->name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">
            @if($svc->cities->isEmpty())
                <span class="text-xs text-green-600 font-medium">All cities</span>
            @else
                <span class="text-xs">{{ $svc->cities->pluck('name')->join(', ') }}</span>
            @endif
        </td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$svc->status" /></td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $svc->sort_order }}</td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.services.edit', $svc) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.services.destroy', $svc)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-text-secondary">No services yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $services->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
