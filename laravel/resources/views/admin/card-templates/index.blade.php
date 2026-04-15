@extends('admin.layouts.app')
@section('title', 'Card Templates')
@section('content')
    <x-admin.flash-message />
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-text mb-1">Card Templates</h1>
            <p class="text-sm text-text-secondary">Visually design repeatable layout cards used in dynamic item grids (e.g.,
                Services, Categories, Portfolio).</p>
        </div>
        <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
            <a href="{{ route('admin.card-templates.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-forest hover:bg-forest-light text-white text-sm font-medium rounded-lg transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i> Create Template
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-stone">
        <div class="p-4 border-b border-stone flex flex-col md:flex-row md:items-center justify-between gap-4 sm:p-6">
            <form method="GET" action="{{ route('admin.card-templates.index') }}" class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center">
                <select name="status"
                    class="px-3 py-2 border border-stone rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest focus:border-transparent"
                    onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </form>
        </div>
        <div class="overflow-x-auto overscroll-x-contain">
            <table class="min-w-[720px] w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-stone-light/50 text-text-secondary">
                    <tr>
                        <th class="px-6 py-4 font-medium">ID</th>
                        <th class="px-6 py-4 font-medium">Template Name</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium">Last Modified</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone">
                    @forelse($templates as $item)
                        <tr class="hover:bg-stone-light/30 transition">
                            <td class="px-6 py-4">
                                <span class="text-text-secondary">{{ $item->id }}</span>
                            </td>
                            <td class="px-6 py-4 font-medium text-text">
                                <a href="{{ route('admin.card-templates.edit', $item) }}"
                                    class="hover:text-forest">{{ $item->name }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-text-secondary">
                                {{ $item->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.card-templates.edit', $item) }}"
                                        class="text-text-secondary hover:text-forest transition" title="Edit">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.card-templates.destroy', $item) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Delete this template? This will break any dynamic loop grids currently using it.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-text-secondary hover:text-red-500 transition"
                                            title="Delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-text-secondary">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="layout" class="w-12 h-12 text-stone mb-3"></i>
                                    <p class="text-base font-medium text-text mb-1">No templates found</p>
                                    <p class="text-sm">Create a card template to reuse inside dynamic loops.</p>
                                    <a href="{{ route('admin.card-templates.create') }}"
                                        class="mt-4 px-4 py-2 bg-forest hover:bg-forest-light text-white text-sm font-medium rounded-lg transition-colors">Create
                                        Template</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($templates->hasPages())
            <div class="p-6 border-t border-stone">
                {{ $templates->links() }}
            </div>
        @endif
    </div>
@endsection
