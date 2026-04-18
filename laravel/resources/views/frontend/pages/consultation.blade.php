@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    title="Project Consultation | Lush Landscape Service"
    description="Begin your project inquiry and request an on-site consultation with Lush Landscape Service. Share a few details and our team will follow up with next steps."
    :canonical="url('/consultation')"
    :schema="$schema"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

@php
    $phone      = \App\Models\Setting::get('phone', '');
    $phoneClean = preg_replace('/[^+\d]/', '', $phone);
@endphp

<section class="section-editorial bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-start">

            {{-- Sidebar info (2 cols) --}}
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h1 class="text-h2 font-heading font-bold text-ink">Project Consultation</h1>
                    <p class="mt-4 text-text-secondary text-lg leading-relaxed">Begin your project inquiry. Share a few details and our team will follow up with next steps.</p>
                </div>

                <div class="bg-forest  p-7 text-white">
                    <h3 class="text-lg font-bold mb-4">What Happens Next?</h3>
                    <ol class="space-y-4">
                        @foreach(['We review your inquiry and confirm fit.','We schedule an on-site consultation.','You receive a clear scope plan and proposal.','You decide on timing and next steps.'] as $i => $step)
                        <li class="flex items-start gap-3">
                            <span class="w-6 h-6  bg-white text-forest text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $i+1 }}</span>
                            <span class="text-white/80 text-sm">{{ $step }}</span>
                        </li>
                        @endforeach
                    </ol>
                </div>

                <div class="space-y-4">
                    @if($phone)
                    <a href="tel:{{ $phoneClean }}" class="flex items-center gap-4 bg-white  border border-stone p-4 hover:border-forest/20 hover:shadow-md transition">
                        <div class="w-10 h-10 bg-forest/10  flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-5 h-5 text-forest"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-secondary font-medium mb-0.5">Prefer to call?</p>
                            <p class="text-sm font-bold text-text">{{ $phone }}</p>
                        </div>
                    </a>
                    @endif
                    <div class="flex flex-wrap gap-2">
                        @foreach(['10-Year Warranty','Licensed & Insured','Premium Materials','Dedicated Project Leads'] as $badge)
                        <span class="text-xs bg-forest/10 text-forest px-3 py-1.5  font-medium flex items-center gap-1.5">
                            <i data-lucide="check" class="w-3 h-3"></i>{{ $badge }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Form (3 cols) --}}
            <div class="lg:col-span-3 bg-white border border-stone p-8">
                <h2 class="text-h2 font-heading font-bold text-ink mb-2">Project Inquiry</h2>
                <p class="text-sm text-text-secondary mb-7">This form helps our team prepare the right next step for your consultation.</p>

                @if($form)
                <div x-data="contactForm('consultation-form', 'consultation')" x-cloak>
                    @php
                        $hiddenHtml = '';
                        if(request('service') || request('city')) {
                            $hiddenHtml .= '<input type="hidden" id="consultation-form-pre_service" name="pre_service" value="'.e(request('service','')).'">';
                            $hiddenHtml .= '<input type="hidden" id="consultation-form-pre_city" name="pre_city" value="'.e(request('city','')).'">';
                        }
                    @endphp
                    @include('frontend.blocks.partials._form-fields', [
                        'form' => $form,
                        'formId' => 'consultation-form',
                        'variant' => 'panel',
                        'tone' => 'light',
                        'labelClass' => 'text-text',
                        'fieldToneClass' => 'bg-white border-stone text-ink placeholder:text-text-secondary',
                        'fieldStyleClass' => '',
                        'fieldColumns' => '2',
                        'buttonClass' => 'btn-luxury btn-luxury-primary w-full py-4 text-base',
                        'submitText' => 'Request a Consultation',
                        'hiddenHtml' => $hiddenHtml
                    ])
                    <p class="text-xs text-text-secondary text-center mt-3">We respect your inbox. Our team follows up with next steps.</p>
                </div>
                @else
                <div class="text-center py-8">
                    <p class="text-text-secondary mb-4">Our inquiry form is temporarily unavailable.</p>
                    @if($phone)
                    <a href="tel:{{ $phoneClean }}" class="btn-luxury btn-luxury-primary inline-flex items-center gap-2">
                        <i data-lucide="phone" class="w-5 h-5"></i>Call {{ $phone }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context ?? []" />
    @endforeach
@endif
@endsection
