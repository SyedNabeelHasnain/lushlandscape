{{-- Block: tabs --}}
@php
    $style = $content['style'] ?? 'underline';
    $tabs = $content['tabs'] ?? [];
@endphp
@if(!empty($tabs))
<div x-data="{ active: 0 }">
    <div class="flex gap-1 {{ $style === 'pills' ? 'bg-stone-light p-1 rounded-lg' : ($style === 'boxed' ? 'border-b border-stone' : 'border-b border-stone') }}" role="tablist">
        @foreach($tabs as $i => $tab)
        <button @click="active = {{ $i }}"
                :class="active === {{ $i }} ? '{{ $style === 'pills' ? 'bg-white shadow-sm' : 'text-forest border-b-2 border-forest' }}' : 'text-text-secondary hover:text-text'"
                class="px-4 py-2 text-sm font-medium transition rounded-md {{ $style === 'pills' ? '' : '-mb-px' }}"
                role="tab">
            @if(!empty($tab['icon']))<i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4 inline mr-1"></i>@endif
            {{ $tab['title'] ?? '' }}
        </button>
        @endforeach
    </div>
    <div class="mt-6">
        @foreach($tabs as $i => $tab)
        <div x-show="active === {{ $i }}" x-cloak role="tabpanel">
            {!! $tab['content'] ?? '' !!}
        </div>
        @endforeach
    </div>
</div>
@endif
