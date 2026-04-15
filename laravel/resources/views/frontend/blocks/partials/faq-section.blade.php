{{-- Block: faq_section --}}
@if($data->isNotEmpty())
@php
    $heading = !empty($content['heading']) ? $content['heading'] : 'Frequently Asked Questions';
    $subtitle = $content['subtitle'] ?? '';
    $style = $content['style'] ?? 'accordion';
@endphp

<div class="mb-10">
    @if($heading)<h2 class="text-3xl font-bold text-text">{{ $heading }}</h2>@endif
    @if($subtitle)<p class="mt-3 text-text-secondary text-lg">{{ $subtitle }}</p>@endif
</div>

<div class="space-y-4">
    @foreach($data as $faq)
    <details class="group bg-white border border-stone rounded-lg" {{ $loop->first ? 'open' : '' }}>
        <summary class="flex items-center justify-between cursor-pointer p-5 font-semibold text-text list-none">
            {{ $faq->question }}
            <i data-lucide="chevron-down" class="w-5 h-5 text-text-secondary transition-transform group-open:rotate-180"></i>
        </summary>
        <div class="px-5 pb-5 text-text-secondary leading-relaxed">
            {!! $faq->answer !!}
        </div>
    </details>
    @endforeach
</div>
@endif
