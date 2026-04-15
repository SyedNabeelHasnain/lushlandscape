@props(['dark' => false])
@php
    $trustItems = [
        [
            'icon'    => 'award',
            'heading' => 'CERTIFIED',
            'desc'    => 'Fully certified landscape professionals with proven expertise in interlocking, concrete, and hardscape construction.',
        ],
        [
            'icon'    => 'shield-check',
            'heading' => 'PROTECTED',
            'desc'    => 'WSIB-compliant with $5M comprehensive liability insurance. Your property is fully covered.',
        ],
        [
            'icon'    => 'file-check',
            'heading' => 'INSURED',
            'desc'    => '10-year workmanship warranty on every project. All permits pulled and documented.',
        ],
    ];
@endphp

<div class="bg-luxury-green-deep">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 md:grid-cols-3">
            @foreach($trustItems as $i => $item)
            <div class="trust-card reveal {{ $i > 0 ? 'border-t md:border-t-0 md:border-l border-white/8' : '' }}">
                <div class="w-12 h-12 border border-white/15 flex items-center justify-center mx-auto mb-5">
                    <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5 text-white/60"></i>
                </div>
                <h3 class="trust-card-heading">{{ $item['heading'] }}</h3>
                <p class="trust-card-desc max-w-xs mx-auto">{{ $item['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
