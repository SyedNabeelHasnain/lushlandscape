{{-- Block: three_column --}}
@php
    $gap = $content['gap'] ?? 'md';
    $children = $block->children->values();
    $columns = [
        $children->filter(fn ($_child, $index) => (data_get($_child->content, '_layout_slot') ?: match ($index % 3) { 0 => 'col1', 1 => 'col2', default => 'col3' }) === 'col1')->values(),
        $children->filter(fn ($_child, $index) => (data_get($_child->content, '_layout_slot') ?: match ($index % 3) { 0 => 'col1', 1 => 'col2', default => 'col3' }) === 'col2')->values(),
        $children->filter(fn ($_child, $index) => (data_get($_child->content, '_layout_slot') ?: match ($index % 3) { 0 => 'col1', 1 => 'col2', default => 'col3' }) === 'col3')->values(),
    ];
    $fallbackColumns = [
        $content['col1_html'] ?? null,
        $content['col2_html'] ?? null,
        $content['col3_html'] ?? null,
    ];
    $gapMap = ['sm' => 'gap-4', 'md' => 'gap-8', 'lg' => 'gap-12'];
    $gapClass = $gapMap[$gap] ?? 'gap-8';
@endphp
<div class="grid grid-cols-1 md:grid-cols-3 {{ $gapClass }}">
    @if($children->isNotEmpty())
        @foreach($columns as $column)
            <div class="space-y-6">
                @foreach($column as $child)
                    <x-frontend.block-renderer :block="$child" :context="$context" />
                @endforeach
            </div>
        @endforeach
    @else
        @foreach($fallbackColumns as $index => $columnHtml)
            <div>
                @if(!empty($columnHtml))
                    {!! $columnHtml !!}
                @else
                    <div class="bg-cream border border-stone-light p-6 min-h-[120px] flex items-center justify-center text-sm text-text-secondary">Column {{ $index + 1 }}</div>
                @endif
            </div>
        @endforeach
    @endif
</div>
