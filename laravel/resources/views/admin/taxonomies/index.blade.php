@extends('admin.layouts.app')

@section('title', 'Taxonomies | Super WMS')
@section('header', 'Taxonomies')

@section('content')
<div class="card p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-serif text-forest">Manage Taxonomies</h2>
        <a href="{{ route('admin.taxonomies.create') }}" class="btn-solid bg-forest text-white px-4 py-2 rounded-sm text-xs font-bold uppercase tracking-widest">Create New</a>
    </div>

    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-stone/50">
                <th class="pb-3 font-semibold text-forest">Name</th>
                <th class="pb-3 font-semibold text-forest">Slug</th>
                <th class="pb-3 font-semibold text-forest">Type</th>
                <th class="pb-3 font-semibold text-forest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone/20">
            @foreach($taxonomies as $tax)
            <tr class="group hover:bg-stone/5 transition-colors">
                <td class="py-4 font-medium text-forest">{{ $tax->name }}</td>
                <td class="py-4 text-stone/70">{{ $tax->slug }}</td>
                <td class="py-4">
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-forest/10 text-forest">
                        {{ $tax->is_hierarchical ? 'Category (Hierarchical)' : 'Tag (Flat)' }}
                    </span>
                </td>
                <td class="py-4 text-right">
                    <a href="{{ route('admin.taxonomies.edit', $tax) }}" class="text-forest hover:text-accent mr-3">Edit</a>
                    <form action="{{ route('admin.taxonomies.destroy', $tax) }}" method="POST" class="inline" onsubmit="return confirm('Delete this taxonomy?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mt-6">
        {{ $taxonomies->links() }}
    </div>
</div>
@endsection