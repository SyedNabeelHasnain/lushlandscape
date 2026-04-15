{{-- Block: cta_button --}}
@php
    $text = $content['text'] ?? 'Learn More';
    $url = $content['url'] ?? '#';
    $style = $content['style'] ?? 'primary';
    $size = $content['size'] ?? 'md';
    $icon = $content['icon'] ?? '';
    $iconPosition = $content['icon_position'] ?? 'right';
    $newTab = $content['open_new_tab'] ?? false;
    
    $styleMap = [
        'primary' => 'bg-forest hover:bg-forest-dark text-white',
        'secondary' => 'bg-ink hover:bg-ink/90 text-white',
        'outline' => 'bg-transparent border-2 border-forest text-forest hover:bg-forest hover:text-white',
        'ghost' => 'bg-transparent text-forest hover:bg-forest/10',
    ];
    $sizeMap = ['sm' => 'text-sm py-2 px-4', 'md' => 'text-base py-3 px-6', 'lg' => 'text-lg py-4 px-8'];
    $classes = $styleMap[$style] ?? $styleMap['primary'];
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
@endphp
<a href="{{ $url }}" class="inline-flex items-center gap-2 font-semibold rounded-lg transition {{ $classes }} {{ $sizeClass }}" {{ $newTab ? 'target="_blank" rel="noopener noreferrer"' : '' }}>
    @if($icon && $iconPosition === 'left')<i data-lucide="{{ $icon }}" class="w-4 h-4"></i>@endif
    {{ $text }}
    @if($icon && $iconPosition === 'right')<i data-lucide="{{ $icon }}" class="w-4 h-4"></i>@endif
</a>
