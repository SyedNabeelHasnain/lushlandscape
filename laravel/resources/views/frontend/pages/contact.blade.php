@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    title="Contact Us | Lush Landscape Service"
    description="Contact Lush Landscape Service. Reach our team by phone, email, or our contact form. We respond within 24 hours. Serving Ontario, Canada."
    :canonical="url('/contact')"
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
    $email      = \App\Models\Setting::get('email', '');
    $address    = \App\Models\Setting::get('address', '');
    $phoneClean = preg_replace('/[^+\d]/', '', $phone);
@endphp

<section class="section-editorial bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <div>
                <h1 class="text-h2 font-heading font-bold text-ink">Contact Us</h1>
                <p class="mt-4 text-text-secondary text-lg leading-relaxed">Have a question or want to learn more? We respond to all inquiries within 24 hours.</p>

                <div class="mt-10 space-y-5">
                    @if($phone)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-forest/10  flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-5 h-5 text-forest"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-secondary uppercase tracking-wide font-semibold mb-0.5">Phone</p>
                            <a href="tel:{{ $phoneClean }}" class="text-lg font-semibold text-text hover:text-forest transition">{{ $phone }}</a>
                            <p class="text-sm text-text-secondary mt-0.5">Mon–Fri 8am–6pm · Sat 9am–4pm</p>
                        </div>
                    </div>
                    @endif
                    @if($email)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-forest/10  flex items-center justify-center shrink-0">
                            <i data-lucide="mail" class="w-5 h-5 text-forest"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-secondary uppercase tracking-wide font-semibold mb-0.5">Email</p>
                            <a href="mailto:{{ $email }}" class="text-base font-medium text-text hover:text-forest transition">{{ $email }}</a>
                        </div>
                    </div>
                    @endif
                    @if($address)
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-forest/10  flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5 text-forest"></i>
                        </div>
                        <div>
                            <p class="text-xs text-text-secondary uppercase tracking-wide font-semibold mb-0.5">Address</p>
                            <p class="text-base text-text">{{ $address }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-10 p-6 bg-forest/10  border border-forest/20">
                    <p class="text-sm font-bold text-text mb-1">Ready to plan your project?</p>
                    <p class="text-sm text-text-secondary mb-4">Book an on-site consultation and receive a clear scope plan with thoughtful material direction.</p>
                    <a href="#contact-form" class="btn-luxury btn-luxury-primary inline-flex items-center gap-2">
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>Book a Consultation
                    </a>
                </div>
            </div>

            <div class="bg-white border border-stone p-8">
                <h2 class="text-h2 font-heading font-bold text-ink mb-6">Send Us a Message</h2>
                @if($form)
                <div x-data="contactForm('contact-form', 'contact-us')" x-cloak>
                    @include('frontend.blocks.partials._form-fields', [
                        'form' => $form,
                        'formId' => 'contact-form',
                        'variant' => 'panel',
                        'tone' => 'light',
                        'labelClass' => 'text-text',
                        'fieldToneClass' => 'bg-white border-stone text-ink placeholder:text-text-secondary',
                        'fieldStyleClass' => '',
                        'fieldColumns' => 'auto',
                        'buttonClass' => 'btn-luxury btn-luxury-primary w-full py-4 text-base',
                        'submitText' => 'Send Message'
                    ])
                </div>
                @else
                <p class="text-text-secondary text-sm">Contact form is temporarily unavailable. Please call or email us directly.</p>
                @endif
            </div>
        </div>
    </div>
</section>

<x-frontend.map-embed />

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@endif
@endsection
