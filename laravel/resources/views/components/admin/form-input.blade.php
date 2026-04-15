@props(['name', 'label', 'type' => 'text', 'value' => '', 'required' => false, 'placeholder' => '', 'help' => '', 'tooltip' => ''])
<div>
    <label for="{{ $name }}" class="flex items-center gap-1.5 text-sm font-medium text-text mb-1.5">
        <span>{{ $label }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif</span>
        <x-admin.tooltip :content="$tooltip" />
    </label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        @if($required) required @endif placeholder="{{ $placeholder }}"
        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm @error($name) border-red-300 @enderror">
    @if($help)<p class="text-xs text-text-secondary mt-1">{{ $help }}</p>@endif
    @error($name)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>
