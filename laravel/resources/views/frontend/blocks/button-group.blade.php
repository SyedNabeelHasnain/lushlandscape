@php
    $buttons = $content['buttons'] ?? [];
    $align   = match($content['align'] ?? 'left') { 'center' => 'justify-center', 'right' => 'justify-end', default => 'justify-start' };
    $stackMobile = !empty($content['stack_mobile']);
@endphp
@if(!empty($buttons))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-6">
    <div class="flex gap-4 {{ $stackMobile ? 'flex-col sm:flex-row' : 'flex-wrap' }} {{ $align }}">
        @foreach($buttons as $btn)
        @if(!empty($btn['url']))
        @php
            $btnStyle = strtolower($btn['style'] ?? 'primary');
            $cls = match($btnStyle) {
                'outline' => 'btn-luxury border-2 border-forest text-forest hover:bg-forest hover:text-white',
                'ghost'   => 'btn-luxury text-forest hover:bg-forest/6',
                default   => 'btn-luxury btn-luxury-primary',
            };
            $newTab = filter_var($btn['new_tab'] ?? false, FILTER_VALIDATE_BOOL);
        @endphp
        <a href="{{ $btn['url'] }}"
           class="inline-flex items-center gap-2 {{ $cls }}"
           @if($newTab) target="_blank" rel="noopener noreferrer" @endif>
            @if(!empty($btn['icon']))
            <i data-lucide="{{ $btn['icon'] }}" class="w-4 h-4"></i>
            @endif
            {{ $btn['text'] ?? 'Button' }}
        </a>
        @endif
        @endforeach
    </div>
</div>
@endif
