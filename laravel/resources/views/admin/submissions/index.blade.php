@extends('admin.layouts.app')
@section('title', 'Form Submissions')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Form Submissions" subtitle="View and manage all form submissions">
    <x-admin.import-export-buttons table="form_submissions" />
</x-admin.page-header>
<div class="mb-6 flex flex-wrap gap-3">
    <form class="flex flex-wrap gap-3" method="GET" x-data="{}">
        <select name="form" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white" x-on:change="$el.form.submit()">
            <option value="">All Forms</option>
            @foreach($forms as $id => $name)<option value="{{ $id }}" {{ request('form') == $id ? 'selected' : '' }}>{{ $name }}</option>@endforeach
        </select>
        <select name="status" class="px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white" x-on:change="$el.form.submit()">
            <option value="">All Statuses</option>
            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
            <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
        </select>
    </form>
</div>
<x-admin.data-table :headers="['Form', 'Summary', 'Status', 'Date']">
    @forelse($submissions as $sub)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $sub->form->name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary max-w-xs truncate">{{ collect($sub->data)->take(2)->implode(', ') }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$sub->status" /></td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $sub->created_at->format('M d, Y H:i') }}</td>
        <td class="px-6 py-4 text-right space-x-3">
            <a href="{{ route('admin.submissions.show', $sub) }}" class="text-forest hover:text-forest-light text-sm">View</a>
            <x-admin.delete-form :route="route('admin.submissions.destroy', $sub)" />
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-text-secondary">No submissions yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $submissions->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
