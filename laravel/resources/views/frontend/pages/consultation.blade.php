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
                <div x-data="contactForm('quote-form', 'consultation')" x-cloak>
                    <form x-on:submit.prevent="submitForm" id="quote-form">
                        <label for="quote-form-website_url_hp" class="sr-only">Leave this field empty</label>
                        <input type="text" id="quote-form-website_url_hp" name="website_url_hp" value="" class="hidden" tabindex="-1" autocomplete="off">
                        @if(request('service') || request('city'))
                        <input type="hidden" id="quote-form-pre_service" name="pre_service" value="{{ request('service','') }}">
                        <input type="hidden" id="quote-form-pre_city" name="pre_city" value="{{ request('city','') }}">
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($form->fields as $field)
                            @php
                                $label = $field->label;
                                $placeholder = $field->placeholder ?? '';
                                $options = $field->options;

                                if ($field->name === 'service') {
                                    $label = 'Project Scope';
                                    $options = [
                                        'Front Entrance and Driveway',
                                        'Rear Yard and Outdoor Living',
                                        'Full Property Transformation',
                                        'Structural Hardscape and Retaining',
                                        'Corrective Repair and Restoration',
                                        'Other',
                                    ];
                                }

                                if ($field->name === 'property_type') {
                                    $label = 'Property Type';
                                    $options = [
                                        'Private Residence',
                                        'Estate Property',
                                        'New Build Residence',
                                        'Ravine Lot',
                                        'Waterfront Property',
                                        'Other',
                                    ];
                                }

                                if ($field->name === 'project_details') {
                                    $label = 'Project Summary';
                                    $placeholder = 'Tell us what you are planning, the timeline you are aiming for, and any material preferences.';
                                }

                                $selectedFromQuery = $field->name === 'service' ? request('service') : null;
                                
                                // Determine autocomplete attribute
                                $autocomplete = 'on';
                                if ($field->type === 'email') {
                                    $autocomplete = 'email';
                                } elseif ($field->type === 'tel') {
                                    $autocomplete = 'tel';
                                } elseif (in_array(strtolower($field->name), ['name', 'full_name', 'fullname'])) {
                                    $autocomplete = 'name';
                                } elseif (in_array(strtolower($field->name), ['first_name', 'firstname'])) {
                                    $autocomplete = 'given-name';
                                } elseif (in_array(strtolower($field->name), ['last_name', 'lastname'])) {
                                    $autocomplete = 'family-name';
                                } elseif (in_array(strtolower($field->name), ['company', 'organization'])) {
                                    $autocomplete = 'organization';
                                } elseif (in_array(strtolower($field->name), ['address'])) {
                                    $autocomplete = 'street-address';
                                } elseif (in_array(strtolower($field->name), ['city'])) {
                                    $autocomplete = 'address-level2';
                                } elseif (in_array(strtolower($field->name), ['postal_code', 'zip', 'zip_code'])) {
                                    $autocomplete = 'postal-code';
                                }
                            @endphp
                            <div class="{{ $field->width === 'full' ? 'md:col-span-2' : '' }}">
                                <label for="qf_{{ $field->name }}" class="block text-sm font-medium text-text mb-1.5">
                                    {{ $label }}@if($field->is_required)<span class="text-red-500 ml-0.5">*</span>@endif
                                </label>
                                @if($field->type === 'textarea')
                                <textarea id="qf_{{ $field->name }}" name="{{ $field->name }}" rows="4" @if($field->is_required) required @endif placeholder="{{ $placeholder }}"
                                    autocomplete="{{ $autocomplete }}"
                                    class="w-full px-4 py-3 border border-stone  focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm resize-none"></textarea>
                                @elseif($field->type === 'select')
                                <select id="qf_{{ $field->name }}" name="{{ $field->name }}" @if($field->is_required) required @endif
                                    autocomplete="{{ $autocomplete }}"
                                    class="w-full px-4 py-3 border border-stone  text-sm bg-white focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                                    <option value="">Select...</option>
                                    @if($options)
                                        @foreach($options as $opt)
                                            <option value="{{ $opt }}" {{ $selectedFromQuery === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @elseif($field->type === 'email')
                                <div class="relative">
                                    <input type="email" id="qf_{{ $field->name }}" name="{{ $field->name }}" @if($field->is_required) required @endif
                                        x-on:blur="checkEmail($event.target.value)"
                                        autocomplete="{{ $autocomplete }}"
                                        class="w-full px-4 py-3 border border-stone  focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                                    <button type="button" x-show="showVerifyBtn" x-cloak x-on:click="sendOtp()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-forest text-white text-xs px-3 py-1.5 ">Verify</button>
                                    <span x-show="emailVerified" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2 text-green-600 text-xs font-semibold">✓ Verified</span>
                                </div>
                                <div x-show="showOtpField" x-cloak class="mt-2 flex gap-2">
                                    <label for="qf_otp_code" class="sr-only">One-time verification code</label>
                                    <input type="text" id="qf_otp_code" name="otp_code" x-model="otpCode" maxlength="6" placeholder="Enter 6-digit code" aria-label="One-time verification code" autocomplete="one-time-code" class="flex-1 px-3 py-2 border border-stone  text-sm">
                                    <button type="button" x-on:click="verifyOtp()" class="bg-forest text-white text-xs px-3 py-2  font-medium">Confirm</button>
                                </div>
                                <p x-show="otpMessage" x-cloak class="text-xs text-text-secondary mt-1" x-text="otpMessage"></p>
                                @else
                                <input type="{{ $field->type }}" id="qf_{{ $field->name }}" name="{{ $field->name }}" @if($field->is_required) required @endif placeholder="{{ $field->placeholder ?? '' }}"
                                    value="{{ $field->name === 'city' ? request('city','') : '' }}"
                                    autocomplete="{{ $autocomplete }}"
                                    class="w-full px-4 py-3 border border-stone  focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <button type="submit" :disabled="formSubmitting || (requiresVerification && !emailVerified)"
                            class="w-full mt-6 btn-luxury btn-luxury-primary py-4 px-6 text-base disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="!formSubmitting" x-cloak>Request a Consultation</span>
                            <span x-show="formSubmitting" x-cloak>Sending...</span>
                        </button>
                        <p class="text-xs text-text-secondary text-center mt-3">We respect your inbox. Our team follows up with next steps.</p>
                        <div x-show="formMessage" x-cloak :class="formSuccess ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'" class="mt-4 p-4  text-sm border" x-text="formMessage"></div>
                    </form>
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
