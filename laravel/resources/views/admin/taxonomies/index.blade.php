@extends('admin.layouts.app')
@section('title', $cfg['label'])
@section('content')
<x-admin.flash-message />
<x-admin.page-header
    :title="$cfg['label']"
    :createRoute="route('admin.' . $cfg['key'] . '.create')"
    createLabel="Add {{ $cfg['singular'] }}"
>
    <x-admin.import-export-buttons :table="str_replace('-', '_', $cfg['key'])" />
</x-admin.page-header>
<x-admin.data-table :headers="['Name', 'Slug', ucfirst($rel), 'Status', 'Order']">
    @forelse($items as $item)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-text">{{ $item->name }}</div>
            @if($item->parent)
                <div class="text-xs text-text-secondary mt-0.5">Under: {{ $item->parent->name }}</div>
            @endif
        </td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $item->slug }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $item->{$rel . '_count'} }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$item->status" /></td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $item->sort_order }}</td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.' . $cfg['key'] . '.edit', $item) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.' . $cfg['key'] . '.destroy', $item)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-text-secondary">No {{ strtolower($cfg['label']) }} yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $items->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
