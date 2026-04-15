@props([
    'route',
    'confirm' => 'Are you sure you want to delete this item? This action cannot be undone.',
])
{{-- AJAX delete — fires DELETE, animates row out, shows toast. --}}
<button type="button"
        data-ajax-delete="{{ $route }}"
        data-confirm="{{ $confirm }}"
        data-tippy-content="Delete"
        class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
</button>
