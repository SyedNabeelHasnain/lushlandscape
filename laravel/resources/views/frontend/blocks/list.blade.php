@php
    $items   = $content['items'] ?? [];
    $style   = $content['style'] ?? 'bullet';
    $cols    = $content['columns'] ?? '1';
    $gridCls = match($cols) { '2' => 'grid-cols-1 sm:grid-cols-2', '3' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3', default => 'grid-cols-1' };
    $tag     = $style === 'numbered' ? 'ol' : 'ul';
@endphp
@if(!empty($items))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
    <{{ $tag }} class="grid {{ $gridCls }} gap-x-8 gap-y-2 {{ $style === 'numbered' ? 'list-decimal list-inside' : '' }}">
        @foreach($items as $item)
        @if(!empty($item['text']))
        <li class="flex items-start gap-2.5 text-text-secondary text-sm">
            @if($style === 'check')
            <i data-lucide="check" class="w-4 h-4 text-forest shrink-0 mt-0.5"></i>
            @elseif($style === 'icon')
            <i data-lucide="circle-dot" class="w-4 h-4 text-forest shrink-0 mt-0.5"></i>
            @elseif($style === 'bullet')
            <span class="w-1.5 h-1.5  bg-forest mt-2 shrink-0"></span>
            @endif
            <span>{{ $item['text'] }}</span>
        </li>
        @endif
        @endforeach
    </{{ $tag }}>
</div>
@endif
