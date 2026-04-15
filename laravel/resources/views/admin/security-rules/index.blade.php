@extends('admin.layouts.app')
@section('title', 'Security Rules')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Security Rules" subtitle="Manage IP, country, and region access rules" :createRoute="route('admin.security-rules.create')" createLabel="Add Rule">
    <x-admin.import-export-buttons table="security_rules" />
</x-admin.page-header>
<x-admin.data-table :headers="['Type', 'Value', 'Action', 'Active']">
    @forelse($rules as $rule)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ ucfirst($rule->type) }}</td>
        <td class="px-6 py-4 text-sm font-mono text-text-secondary">{{ $rule->value }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$rule->action === 'block' ? 'archived' : 'active'" /></td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$rule->is_active ? 'active' : 'inactive'" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.security-rules.edit', $rule) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.security-rules.destroy', $rule)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-text-secondary">No security rules yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $rules->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
