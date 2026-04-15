@extends('admin.layouts.app')
@section('title', 'Blog Posts')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Blog Posts" :createRoute="route('admin.blog-posts.create')" createLabel="New Post">
    <x-admin.import-export-buttons table="blog_posts" />
</x-admin.page-header>
<x-admin.data-table :headers="['Title', 'Category', 'Author', 'Status', 'Published']">
    @forelse($posts as $post)
    <tr class="hover:bg-gray-50 transition" data-delete-row>
        <td class="px-6 py-4 text-sm font-medium text-text max-w-xs truncate">{{ $post->title }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $post->category->name ?? '-' }}</td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $post->author->name ?? '-' }}</td>
        <td class="px-6 py-4"><x-admin.status-badge :status="$post->status" /></td>
        <td class="px-6 py-4 text-sm text-text-secondary">{{ $post->published_at?->format('M d, Y') ?? '-' }}</td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end gap-1">
                <a href="{{ route('admin.blog-posts.edit', $post) }}" data-tippy-content="Edit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-forest hover:bg-forest-50 transition"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></a>
                <x-admin.delete-form :route="route('admin.blog-posts.destroy', $post)" />
            </div>
        </td>
    </tr>
    @empty
    <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-text-secondary">No blog posts yet.</td></tr>
    @endforelse
    <x-slot:pagination>{{ $posts->links() }}</x-slot:pagination>
</x-admin.data-table>
@endsection
