@props(['title' => null, 'padding' => true])
<div class="bg-white rounded-2xl border border-gray-100">
    @if($title)
    <div class="px-4 py-4 border-b border-gray-100 sm:px-6">
        <h2 class="text-base font-semibold text-text">{{ $title }}</h2>
    </div>
    @endif
    <div class="{{ $padding ? 'p-4 sm:p-6' : '' }}">{{ $slot }}</div>
</div>
