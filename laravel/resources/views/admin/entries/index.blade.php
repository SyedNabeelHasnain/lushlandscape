@extends('admin.layouts.app')

@section('title', 'Entries | Super WMS')
@section('header', 'Entries ' . ($contentType ? ' - ' . $contentType->name : ''))

@section('content')
<div class="card p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-4 items-center">
            <h2 class="text-xl font-serif text-forest">Manage Entries</h2>
            @if($contentType)
            <span class="bg-forest/10 text-forest px-3 py-1 rounded-full text-xs font-bold">{{ $contentType->name }}</span>
            @endif
        </div>
        <div class="flex gap-4">
            <form method="GET" class="flex gap-2">
                <select name="type" class="field-ui rounded-sm text-sm" onchange="this.form.submit()">
                    <option value="">All Content Types</option>
                    @foreach($contentTypes as $id => $name)
                        <option value="{{ $id }}" {{ request('type') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="field-ui rounded-sm text-sm">
                <button type="submit" class="btn-outline px-4 py-2 border border-stone/50 rounded-sm">Filter</button>
            </form>
            @if($contentType)
            <a href="{{ route('admin.entries.create', ['type' => $contentType->id]) }}" class="btn-solid bg-forest text-white px-4 py-2 rounded-sm text-xs font-bold uppercase tracking-widest">Create New</a>
            @endif
        </div>
    </div>

    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-stone/50">
                <th class="pb-3 font-semibold text-forest">Title</th>
                <th class="pb-3 font-semibold text-forest">Type</th>
                <th class="pb-3 font-semibold text-forest">Slug</th>
                <th class="pb-3 font-semibold text-forest">Status</th>
                <th class="pb-3 font-semibold text-forest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone/20">
            @foreach($entries as $entry)
            <tr class="group hover:bg-stone/5 transition-colors">
                <td class="py-4 font-medium text-forest">{{ $entry->title }}</td>
                <td class="py-4 text-stone/70 text-xs uppercase tracking-widest">{{ $entry->contentType->name }}</td>
                <td class="py-4 text-stone/70">/{{ $entry->slug }}</td>
                <td class="py-4">
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $entry->status === 'published' ? 'bg-forest/10 text-forest' : 'bg-amber-100 text-amber-800' }}">
                        {{ $entry->status }}
                    </span>
                </td>
                <td class="py-4 text-right">
                    <a href="{{ route('admin.entries.edit', $entry) }}" class="text-forest hover:text-accent mr-3">Edit</a>
                    <a href="{{ url($entry->routeAlias ? $entry->routeAlias->slug : 'wms/' . $entry->slug) }}" target="_blank" class="text-forest/50 hover:text-forest">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="mt-6">
        {{ $entries->withQueryString()->links() }}
    </div>
</div>
@endsection
