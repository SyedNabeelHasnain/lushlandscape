@extends('admin.layouts.app')

@section('title', 'Terms in ' . $taxonomy->name . ' | Super WMS')
@section('header', 'Terms: ' . $taxonomy->name)

@section('content')
<div class="card p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('admin.taxonomies.index') }}" class="text-xs font-semibold text-stone/50 hover:text-forest uppercase tracking-widest mb-2 block">&larr; Back to Taxonomies</a>
            <h2 class="text-xl font-serif text-forest">{{ $taxonomy->name }}</h2>
        </div>
        <a href="{{ route('admin.taxonomies.terms.create', $taxonomy) }}" class="btn-solid bg-forest text-white px-4 py-2 rounded-sm text-xs font-bold uppercase tracking-widest">Create Term</a>
    </div>

    @if($terms->isEmpty())
        <div class="text-center py-12 text-stone/50">
            <i data-lucide="tags" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p>No terms found in this taxonomy.</p>
        </div>
    @else
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-stone/50">
                    <th class="pb-3 font-semibold text-forest">Name</th>
                    <th class="pb-3 font-semibold text-forest">Slug</th>
                    @if($taxonomy->is_hierarchical)
                    <th class="pb-3 font-semibold text-forest">Parent</th>
                    @endif
                    <th class="pb-3 font-semibold text-forest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone/20">
                @foreach($terms as $term)
                <tr class="group hover:bg-stone/5 transition-colors">
                    <td class="py-4 font-medium text-forest">
                        <a href="{{ route('admin.taxonomies.terms.edit', [$taxonomy, $term]) }}" class="hover:text-accent">{{ $term->name }}</a>
                    </td>
                    <td class="py-4 text-stone/70">{{ $term->slug }}</td>
                    @if($taxonomy->is_hierarchical)
                    <td class="py-4 text-stone/70">{{ $term->parent ? $term->parent->name : '—' }}</td>
                    @endif
                    <td class="py-4 text-right">
                        <a href="{{ route('admin.taxonomies.terms.edit', [$taxonomy, $term]) }}" class="text-forest hover:text-accent mr-3">Edit</a>
                        <form action="{{ route('admin.taxonomies.terms.destroy', [$taxonomy, $term]) }}" method="POST" class="inline" onsubmit="return confirm('Delete this term?')">
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
            {{ $terms->links() }}
        </div>
    @endif
</div>
@endsection