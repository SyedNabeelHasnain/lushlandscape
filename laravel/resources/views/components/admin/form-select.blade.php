@props(['name', 'label', 'options' => [], 'value' => '', 'required' => false, 'placeholder' => 'Select...', 'help' => '', 'tooltip' => ''])
<div>
    <label for="{{ $name }}" class="flex items-center gap-1.5 text-sm font-medium text-text mb-1.5">
        <span>{{ $label }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif</span>
        <x-admin.tooltip :content="$tooltip" />
    </label>
    <select id="{{ $name }}" name="{{ $name }}" @if($required) required @endif
        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm bg-white @error($name) border-red-300 @enderror">
        <option value="">{{ $placeholder }}</option>
        @foreach($options as $k => $v)
            <option value="{{ $k }}" {{ old($name, $value) == $k ? 'selected' : '' }}>{{ $v }}</option>
        @endforeach
    </select>
    @if($help)<p class="text-xs text-text-secondary mt-1">{{ $help }}</p>@endif
    @error($name)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>
