@props(['name', 'label', 'checked' => false, 'help' => '', 'tooltip' => ''])
<div class="flex items-start gap-3">
    <div class="flex items-center h-6">
        <input type="hidden" name="{{ $name }}" value="0">
        <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1" {{ old($name, $checked) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest">
    </div>
    <div>
        <div class="flex items-center gap-1.5">
            <label for="{{ $name }}" class="text-sm font-medium text-text cursor-pointer">{{ $label }}</label>
            <x-admin.tooltip :content="$tooltip" />
        </div>
        @if($help)<p class="text-xs text-text-secondary mt-0.5">{{ $help }}</p>@endif
    </div>
</div>
