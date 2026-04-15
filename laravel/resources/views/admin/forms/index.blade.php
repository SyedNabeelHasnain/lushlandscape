@extends('admin.layouts.app')
@section('title', 'Forms')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Forms Management" :createRoute="route('admin.forms.create')" createLabel="Add Form">
    <x-admin.import-export-buttons table="forms" />
</x-admin.page-header>
<x-admin.data-table :headers="['Name', 'Type', 'Submissions', 'Email Verify', 'Status']">
    @forelse($forms as $form)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $form->name }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $form->form_type }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $form->submissions_count }}</td>
        <td class="px-6 py-4 text-sm">{{ $form->requires_email_verification ? 'Yes' : 'No' }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$form->status" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.forms.edit', $form) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.forms.destroy', $form)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-text-secondary">No forms yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $forms->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
