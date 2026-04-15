@extends('admin.layouts.app')
@section('title', 'Popups')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Popups" subtitle="Manage on-site popup messages and lead capture forms" :createRoute="route('admin.popups.create')" createLabel="New Popup">
    <x-admin.import-export-buttons table="popups" />
</x-admin.page-header>

<x-admin.data-table :headers="['Name', 'Trigger', 'Suppress', 'Mobile', 'Status', 'Dates']">
    @forelse($popups as $popup)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4">
            <p class="text-sm font-medium text-text">{{ $popup->name }}</p>
            @if($popup->heading)<p class="text-xs text-text-secondary mt-0.5">{{ Str::limit($popup->heading, 50) }}</p>@endif
        </td>
        <td class="px-6 py-4 text-sm text-text-secondary">
            @if($popup->trigger_type === 'delay')
                After {{ $popup->trigger_delay_seconds }}s
            @elseif($popup->trigger_type === 'scroll_percent')
                Scroll {{ $popup->trigger_scroll_percent }}%
            @else
                Exit intent
            @endif
        </td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $popup->suppress_days }}d</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $popup->show_on_mobile ? 'Yes' : 'No' }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$popup->status" /></td>
        <td class="px-6 py-4 text-xs text-text-secondary">
            @if($popup->starts_at || $popup->ends_at)
                {{ $popup->starts_at?->format('M j') ?? '∞' }} → {{ $popup->ends_at?->format('M j, Y') ?? '∞' }}
            @else
                <span class="text-gray-400">Always</span>
            @endif
        </td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.popups.edit', $popup) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.popups.destroy', $popup)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-text-secondary">No popups yet. Create one to get started.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $popups->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
