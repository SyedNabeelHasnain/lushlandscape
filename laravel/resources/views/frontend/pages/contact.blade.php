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
                    <form x-on:submit.prevent="submitForm" id="contact-form">
                        <input type="text" name="website_url_hp" value="" class="hidden" tabindex="-1" autocomplete="off">
                        <div class="space-y-5">
                            @foreach($form->fields as $field)
                            <div>
                                <label for="cf_{{ $field->name }}" class="block text-sm font-medium text-text mb-1.5">
                                    {{ $field->label }}@if($field->is_required)<span class="text-red-500 ml-0.5">*</span>@endif
                                </label>
                                @if($field->type === 'textarea')
                                <textarea id="cf_{{ $field->name }}" name="{{ $field->name }}" rows="4" @if($field->is_required) required @endif placeholder="{{ $field->placeholder ?? '' }}"
                                    class="w-full px-4 py-3 border border-stone  focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm resize-none"></textarea>
                                @elseif($field->type === 'select')
                                <select id="cf_{{ $field->name }}" name="{{ $field->name }}" @if($field->is_required) required @endif
                                    class="w-full px-4 py-3 border border-stone  text-sm bg-white focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                                    <option value="">Select...</option>
                                    @if($field->options)
                                    @foreach($field->options as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                @elseif($field->type === 'email')
                                <div class="relative">
                                    <input type="email" id="cf_{{ $field->name }}" name="{{ $field->name }}" @if($field->is_required) required @endif
                                        x-on:blur="checkEmail($event.target.value)"
                                        class="w-full px-4 py-3 border border-stone  focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                                    <button type="button" x-show="showVerifyBtn" x-on:click="sendOtp()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-forest text-white text-xs px-3 py-1.5 ">Verify</button>
                                    <span x-show="emailVerified" class="absolute right-3 top-1/2 -translate-y-1/2 text-green-600 text-xs font-semibold">✓ Verified</span>
                                </div>
                                <div x-show="showOtpField" class="mt-2 flex gap-2">
                                    <input type="text" x-model="otpCode" maxlength="6" placeholder="Enter 6-digit code" aria-label="One-time verification code" class="flex-1 px-3 py-2 border border-stone  text-sm">
                                    <button type="button" x-on:click="verifyOtp()" class="bg-forest text-white text-xs px-3 py-2  font-medium">Confirm</button>
                                </div>
                                <p x-show="otpMessage" class="text-xs text-text-secondary mt-1" x-text="otpMessage"></p>
                                @else
                                <input type="{{ $field->type }}" id="cf_{{ $field->name }}" name="{{ $field->name }}" @if($field->is_required) required @endif placeholder="{{ $field->placeholder ?? '' }}"
                                    class="w-full px-4 py-3 border border-stone  focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" :disabled="formSubmitting || (requiresVerification && !emailVerified)"
                            class="w-full mt-6 btn-luxury btn-luxury-primary py-4 px-6 text-base disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!formSubmitting">Send Message</span>
                            <span x-show="formSubmitting">Sending...</span>
                        </button>
                        <div x-show="formMessage" :class="formSuccess ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'" class="mt-4 p-4  text-sm border" x-text="formMessage"></div>
                    </form>
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
