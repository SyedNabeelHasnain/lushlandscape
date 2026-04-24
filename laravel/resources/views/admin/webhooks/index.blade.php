@extends('admin.layouts.app')

@section('title', 'Webhooks | Super WMS')
@section('header', 'Webhooks')

@section('content')
<div class="card p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-serif text-forest">Manage Webhooks</h2>
        <a href="{{ route('admin.webhooks.create') }}" class="btn-solid bg-forest text-white px-4 py-2 rounded-sm text-xs font-bold uppercase tracking-widest">Create New</a>
    </div>

    @if($webhooks->isEmpty())
        <div class="text-center py-12 text-stone/50">
            <i data-lucide="webhook" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p>No webhooks configured. Create one to send data to external systems.</p>
        </div>
    @else
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-stone/50">
                    <th class="pb-3 font-semibold text-forest">Name</th>
                    <th class="pb-3 font-semibold text-forest">Event</th>
                    <th class="pb-3 font-semibold text-forest">Target URL</th>
                    <th class="pb-3 font-semibold text-forest">Status</th>
                    <th class="pb-3 font-semibold text-forest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone/20">
                @foreach($webhooks as $webhook)
                <tr class="group hover:bg-stone/5 transition-colors">
                    <td class="py-4 font-medium text-forest">
                        <a href="{{ route('admin.webhooks.edit', $webhook) }}" class="hover:text-accent">{{ $webhook->name }}</a>
                    </td>
                    <td class="py-4 text-stone/70"><span class="px-2 py-1 bg-stone/10 rounded-sm text-xs">{{ $webhook->event }}</span></td>
                    <td class="py-4 text-stone/70 truncate max-w-xs" title="{{ $webhook->url }}">{{ $webhook->url }}</td>
                    <td class="py-4">
                        @if($webhook->is_active)
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-stone-100 text-stone-600">Inactive</span>
                        @endif
                    </td>
                    <td class="py-4 text-right">
                        <a href="{{ route('admin.webhooks.edit', $webhook) }}" class="text-forest hover:text-accent mr-3">Edit</a>
                        <form action="{{ route('admin.webhooks.destroy', $webhook) }}" method="POST" class="inline" onsubmit="return confirm('Delete this webhook?')">
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
            {{ $webhooks->links() }}
        </div>
    @endif
</div>
@endsection