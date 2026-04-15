{{-- Block: two_column --}}
@php
    $ratio = $content['ratio'] ?? '1:1';
    $reverseMobile = $content['reverse_mobile'] ?? false;
    $gap = $content['gap'] ?? 'md';
    $children = $block->children->values();
    $leftChildren = $children->filter(fn ($_child, $index) => (data_get($_child->content, '_layout_slot') ?: (($index % 2 === 0) ? 'left' : 'right')) === 'left')->values();
    $rightChildren = $children->filter(fn ($_child, $index) => (data_get($_child->content, '_layout_slot') ?: (($index % 2 === 0) ? 'left' : 'right')) === 'right')->values();
    $ratioMap = ['1:1' => 'md:grid-cols-2', '1:2' => 'md:grid-cols-3', '2:1' => 'md:grid-cols-3'];
    $gapMap = ['sm' => 'gap-4', 'md' => 'gap-8', 'lg' => 'gap-12'];
    $colClass = $ratioMap[$ratio] ?? 'md:grid-cols-2';
    $gapClass = $gapMap[$gap] ?? 'gap-8';
    $leftOrderClass = $reverseMobile ? 'order-2 md:order-1' : 'order-1';
    $rightOrderClass = $reverseMobile ? 'order-1 md:order-2' : 'order-2';
@endphp
<div class="grid {{ $colClass }} {{ $gapClass }}">
    <div class="{{ $leftOrderClass }}">
        @if($leftChildren->isNotEmpty())
            @foreach($leftChildren as $child)
                <x-frontend.block-renderer :block="$child" :context="$context" />
            @endforeach
        @elseif(!empty($content['left_html']))
            {!! $content['left_html'] !!}
        @endif
    </div>
    <div class="{{ $rightOrderClass }}">
        @if($rightChildren->isNotEmpty())
            @foreach($rightChildren as $child)
                <x-frontend.block-renderer :block="$child" :context="$context" />
            @endforeach
        @elseif(!empty($content['right_html']))
            {!! $content['right_html'] !!}
        @endif
    </div>
</div>
