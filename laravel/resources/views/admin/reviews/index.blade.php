@extends('admin.layouts.app')
@section('title', 'Reviews')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Reviews & Testimonials" :createRoute="route('admin.reviews.create')" createLabel="Add Review">
    <x-admin.import-export-buttons table="reviews" />
</x-admin.page-header>
<x-admin.data-table :headers="['Reviewer', 'Rating', 'City', 'Service', 'Status']">
    @forelse($reviews as $rev)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text">{{ $rev->reviewer_name }}</td>
        <td class="px-6 py-4 text-sm text-yellow-500">{{ str_repeat('*', $rev->rating) }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $rev->city_relevance ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $rev->service_relevance ?? '-' }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$rev->status" /></td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.reviews.edit', $rev) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.reviews.destroy', $rev)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-text-secondary">No reviews yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $reviews->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
