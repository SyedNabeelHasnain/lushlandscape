@props(['status'])
@php
$colors = match($status) {
    'published', 'active', 'verified', 'success' => 'bg-green-100 text-green-700',
    'draft' => 'bg-yellow-100 text-yellow-700',
    'archived', 'inactive' => 'bg-gray-100 text-gray-700',
    'new' => 'bg-blue-100 text-blue-700',
    'read', 'reviewed' => 'bg-purple-100 text-purple-700',
    default => 'bg-gray-100 text-gray-700',
};
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors }}">{{ ucfirst($status) }}</span>
