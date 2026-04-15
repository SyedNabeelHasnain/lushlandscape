@php
    $type = $content['type'] ?? 'info';
    $styles = match($type) {
        'success' => ['bg' => 'bg-forest/5 border-forest/20', 'icon' => 'check-circle', 'iconClr' => 'text-forest', 'textClr' => 'text-ink', 'titleClr' => 'text-ink'],
        'warning' => ['bg' => 'bg-amber-50 border-amber-200', 'icon' => 'alert-triangle', 'iconClr' => 'text-amber-600', 'textClr' => 'text-amber-800', 'titleClr' => 'text-amber-900'],
        'error'   => ['bg' => 'bg-red-50 border-red-200', 'icon' => 'x-circle', 'iconClr' => 'text-red-600', 'textClr' => 'text-red-800', 'titleClr' => 'text-red-900'],
        'tip'     => ['bg' => 'bg-cream border-stone', 'icon' => 'lightbulb', 'iconClr' => 'text-forest', 'textClr' => 'text-ink', 'titleClr' => 'text-ink'],
        default   => ['bg' => 'bg-cream border-stone', 'icon' => 'info', 'iconClr' => 'text-forest', 'textClr' => 'text-ink', 'titleClr' => 'text-ink'],
    };
    $dismissible = !empty($content['dismissible']);
@endphp
@if(!empty($content['text']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
    <div class="{{ $styles['bg'] }} border p-5 md:p-6 flex gap-4"
         @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif>
        <i data-lucide="{{ $styles['icon'] }}" class="w-5 h-5 {{ $styles['iconClr'] }} shrink-0 mt-0.5"></i>
        <div class="flex-1 min-w-0">
            @if(!empty($content['title']))
            <p class="font-semibold {{ $styles['titleClr'] }} mb-1">{{ $content['title'] }}</p>
            @endif
            <p class="text-sm {{ $styles['textClr'] }} leading-relaxed">{{ $content['text'] }}</p>
        </div>
        @if($dismissible)
        <button type="button" x-on:click="show = false" class="{{ $styles['iconClr'] }} opacity-60 hover:opacity-100 shrink-0 transition-opacity" aria-label="Dismiss alert">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
        @endif
    </div>
</div>
@endif
