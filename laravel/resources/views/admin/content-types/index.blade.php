@extends('admin.layouts.app')

@section('title', 'Content Types | Super WMS')
@section('header', 'Content Types')

@section('content')
<div class="card p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-serif text-forest">Manage Content Types</h2>
        <a href="{{ route('admin.content-types.create') }}" class="btn-solid bg-forest text-white px-4 py-2 rounded-sm text-xs font-bold uppercase tracking-widest">Create New</a>
    </div>

    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-stone/50">
                <th class="pb-3 font-semibold text-forest">Name</th>
                <th class="pb-3 font-semibold text-forest">Slug</th>
                <th class="pb-3 font-semibold text-forest">Entries</th>
                <th class="pb-3 font-semibold text-forest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone/20">
            @foreach($contentTypes as $type)
            <tr class="group hover:bg-stone/5 transition-colors">
                <td class="py-4 font-medium text-forest">{{ $type->name }}</td>
                <td class="py-4 text-stone/70">{{ $type->slug }}</td>
                <td class="py-4"><a href="{{ route('admin.entries.index', ['type' => $type->id]) }}" class="text-accent hover:underline">{{ $type->entries()->count() }} Entries</a></td>
                <td class="py-4 text-right">
                    <a href="{{ route('admin.content-types.edit', $type) }}" class="text-forest hover:text-accent mr-3">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mt-6">
        {{ $contentTypes->links() }}
    </div>
</div>
@endsection
