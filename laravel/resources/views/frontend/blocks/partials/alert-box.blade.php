{{-- Block: alert_box --}}
@php
    $text = $content['text'] ?? '';
    $title = $content['title'] ?? '';
    $type = $content['type'] ?? 'info';
    $dismissible = $content['dismissible'] ?? false;
    $typeClasses = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-800',
        'success' => 'bg-green-50 border-green-200 text-green-800',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800',
        'error' => 'bg-red-50 border-red-200 text-red-800',
        'tip' => 'bg-forest/5 border-forest/20 text-forest',
    ];
    $typeIcons = ['info' => 'info', 'success' => 'check-circle', 'warning' => 'alert-triangle', 'error' => 'x-circle', 'tip' => 'lightbulb'];
    $classes = $typeClasses[$type] ?? $typeClasses['info'];
    $icon = $typeIcons[$type] ?? 'info';
@endphp
@if($text)
<div class="border rounded-lg p-5 {{ $classes }}" {{ $dismissible ? 'x-data="{ show: true }" x-show="show"' : '' }}>
    <div class="flex items-start gap-3">
        <i data-lucide="{{ $icon }}" class="w-5 h-5 mt-0.5 shrink-0"></i>
        <div class="flex-1">
            @if($title)<p class="font-semibold mb-1">{{ $title }}</p>@endif
            <p class="text-sm">{{ $text }}</p>
        </div>
        @if($dismissible)
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
        @endif
    </div>
</div>
@endif
