@php
    $form = $block['data'] ?? null;
    $phone = \App\Models\Setting::get('phone', '');
    $phoneClean = preg_replace('/[^+\d]/', '', $phone);
    $emailAddress = \App\Models\Setting::get('email', '');
@endphp
<section class="bg-airy-gradient py-20 lg:py-32 px-5 lg:px-12 min-h-screen flex items-center justify-center section-fade-to-white">
    <div class="max-w-6xl w-full mx-auto flex flex-col lg:flex-row gap-12 lg:gap-24 relative">
        
        {{-- Left: Sticky Context & Trust Anchors --}}
        <div class="w-full lg:w-5/12 lg:sticky lg:top-40 h-fit gs-reveal z-10">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'Project Intake' }}</p>
            <h2 class="fluid-heading text-forest mb-4 lg:mb-6 word-wrap-safe">{!! $content['heading'] ?? 'Request a<br>Design Consultation' !!}</h2>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light mb-10 lg:mb-12 max-w-sm">{{ $content['description'] }}</p>
            @endif

            {{-- Trust Badges --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 border-t border-forest/10 pt-8 lg:pt-10">
                @for($i=1; $i<=4; $i++)
                    @if(!empty($content["badge_{$i}_text"]))
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-white border border-forest/10 flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $content["badge_{$i}_icon"] ?? 'check' }}" class="w-4 h-4 text-forest-light"></i>
                        </div>
                        <span class="text-xs lg:text-sm font-semibold text-forest uppercase tracking-[0.1em] pt-2">{{ $content["badge_{$i}_text"] }}</span>
                    </div>
                    @endif
                @endfor
            </div>

            @if($phone || $emailAddress)
            <div class="mt-10 lg:mt-12 space-y-4 pt-8 lg:pt-10 border-t border-forest/10">
                @if($phone)
                <a href="tel:{{ $phoneClean }}" class="flex items-center gap-3 text-ink/60 hover:text-forest transition-colors">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    <span class="text-sm font-medium tracking-wide">{{ $phone }}</span>
                </a>
                @endif
                @if($emailAddress)
                <a href="mailto:{{ $emailAddress }}" class="flex items-center gap-3 text-ink/60 hover:text-forest transition-colors">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span class="text-sm font-medium tracking-wide">{{ $emailAddress }}</span>
                </a>
                @endif
            </div>
            @endif
        </div>
        
        {{-- Right: The Wizard Form --}}
        <div class="w-full lg:w-7/12 relative z-10 gs-reveal">
            @if($form)
            <div x-data="luxuryWizard('{{ $form->slug }}')" class="bg-white p-8 lg:p-14 border border-black/5 shadow-xl relative min-h-[500px] flex flex-col justify-between" x-cloak>
                
                {{-- Progress Bar --}}
                <div class="mb-10 lg:mb-12 relative" x-show="!formSuccess">
                    <div class="flex justify-between mb-2">
                        <span class="text-[9px] uppercase tracking-[0.2em] font-bold" :class="step >= 1 ? 'text-forest' : 'text-ink/30'">Scope</span>
                        <span class="text-[9px] uppercase tracking-[0.2em] font-bold" :class="step >= 2 ? 'text-forest' : 'text-ink/30'">Property</span>
                        <span class="text-[9px] uppercase tracking-[0.2em] font-bold" :class="step >= 3 ? 'text-forest' : 'text-ink/30'">Contact</span>
                    </div>
                    <div class="h-[2px] w-full bg-stone/50 relative">
                        <div class="absolute top-0 left-0 h-full bg-accent transition-all duration-500 ease-out" :style="'width: ' + ((step - 1) / 2 * 100) + '%'"></div>
                    </div>
                </div>

                <form @submit.prevent="submitWizard" id="luxury-wizard-form" class="flex-1 flex flex-col justify-between" :class="{ 'opacity-50 pointer-events-none': isSubmitting }">
                    
                    {{-- STEP 1: Vision (Service & City) --}}
                    <div x-show="step === 1" x-transition.opacity.duration.500ms class="space-y-8">
                        <h3 class="text-2xl font-serif text-forest mb-6">What are we building?</h3>
                        
                        <div class="field-wrapper">
                            <select x-model="formData.service" class="field-ui pr-10" :class="formData.service ? 'has-value' : ''" required>
                                <option value="" disabled selected hidden></option>
                                <option value="Front Entrance and Driveway">Front Entrance and Driveway</option>
                                <option value="Rear Yard and Outdoor Living">Rear Yard and Outdoor Living</option>
                                <option value="Full Property Transformation">Full Property Transformation</option>
                                <option value="Structural Hardscape and Retaining">Structural Hardscape and Retaining</option>
                                <option value="Corrective Repair and Restoration">Corrective Repair and Restoration</option>
                                <option value="Other">Other</option>
                            </select>
                            <label class="field-label">Project Scope *</label>
                            <i data-lucide="chevron-down" class="absolute right-0 top-3 w-4 h-4 text-forest/40 pointer-events-none"></i>
                        </div>

                        <div class="field-wrapper">
                            <select x-model="formData.city" class="field-ui pr-10" :class="formData.city ? 'has-value' : ''" required>
                                <option value="" disabled selected hidden></option>
                                <option value="Toronto">Toronto</option>
                                <option value="Oakville">Oakville</option>
                                <option value="Mississauga">Mississauga</option>
                                <option value="Burlington">Burlington</option>
                                <option value="Vaughan">Vaughan</option>
                                <option value="Richmond Hill">Richmond Hill</option>
                                <option value="Hamilton">Hamilton</option>
                                <option value="Milton">Milton</option>
                                <option value="Georgetown">Georgetown</option>
                                <option value="Brampton">Brampton</option>
                            </select>
                            <label class="field-label">City *</label>
                            <i data-lucide="chevron-down" class="absolute right-0 top-3 w-4 h-4 text-forest/40 pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- STEP 2: Property & Details --}}
                    <div x-show="step === 2" x-transition.opacity.duration.500ms class="space-y-8" style="display: none;">
                        <h3 class="text-2xl font-serif text-forest mb-6">Tell us about the property.</h3>
                        
                        <div class="field-wrapper">
                            <select x-model="formData.property_type" class="field-ui pr-10" :class="formData.property_type ? 'has-value' : ''">
                                <option value="" disabled selected hidden></option>
                                <option value="Private Residence">Private Residence</option>
                                <option value="Estate Property">Estate Property</option>
                                <option value="New Build Residence">New Build Residence</option>
                                <option value="Ravine Lot">Ravine Lot</option>
                                <option value="Waterfront Property">Waterfront Property</option>
                                <option value="Other">Other</option>
                            </select>
                            <label class="field-label">Property Type</label>
                            <i data-lucide="chevron-down" class="absolute right-0 top-3 w-4 h-4 text-forest/40 pointer-events-none"></i>
                        </div>

                        <div class="field-wrapper">
                            <textarea x-model="formData.project_details" class="field-ui min-h-[120px] resize-none" :class="formData.project_details ? 'has-value' : ''" placeholder=" " required></textarea>
                            <label class="field-label">Project Summary *</label>
                        </div>
                    </div>

                    {{-- STEP 3: Contact & Verification --}}
                    <div x-show="step === 3" x-transition.opacity.duration.500ms class="space-y-8" style="display: none;">
                        <h3 class="text-2xl font-serif text-forest mb-6">How should we reach you?</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="field-wrapper">
                                <input type="text" x-model="formData.full_name" class="field-ui" :class="formData.full_name ? 'has-value' : ''" placeholder=" " required>
                                <label class="field-label">Full Name *</label>
                            </div>
                            <div class="field-wrapper">
                                <input type="tel" x-model="formData.phone" class="field-ui" :class="formData.phone ? 'has-value' : ''" placeholder=" " required>
                                <label class="field-label">Phone Number *</label>
                            </div>
                        </div>

                        <div class="field-wrapper relative">
                            <input type="email" x-model="formData.email" @blur="checkEmail" class="field-ui pr-24" :class="formData.email ? 'has-value' : ''" placeholder=" " :readonly="emailVerified" required>
                            <label class="field-label">Email Address *</label>
                            
                            <button type="button" x-show="showVerifyBtn && !showOtpField && !emailVerified" @click="sendOtp" class="absolute right-0 top-2.5 rounded-sm bg-forest px-4 py-1.5 text-[9px] font-bold uppercase tracking-[0.15em] text-white hover:bg-accent transition-colors">Verify</button>
                            <span x-show="emailVerified" class="absolute right-0 top-3 text-[10px] font-bold uppercase tracking-[0.1em] text-forest-light flex items-center gap-1"><i data-lucide="check-circle-2" class="w-3 h-3"></i> Verified</span>
                        </div>

                        <div x-show="showOtpField" x-collapse class="bg-forest/5 p-6 border border-forest/10 mt-4 relative">
                            <p class="text-xs text-forest mb-4 font-medium">Please enter the 6-digit code sent to your email.</p>
                            <div class="flex gap-4">
                                <input type="text" x-model="otpCode" maxlength="6" class="field-ui !bg-white !border-forest/20 text-center tracking-[0.5em] font-bold text-lg" placeholder="••••••">
                                <button type="button" @click="verifyOtp" class="btn-solid px-6 text-xs uppercase tracking-widest" :disabled="otpCode.length !== 6">Confirm</button>
                            </div>
                            <p x-show="otpMessage" class="text-xs mt-3" :class="otpMessage.includes('failed') || otpMessage.includes('Error') ? 'text-red-600' : 'text-accent'" x-text="otpMessage"></p>
                        </div>
                        
                        <p x-show="formMessage && !formSuccess" class="text-xs text-red-600 font-medium" x-text="formMessage"></p>
                    </div>

                    {{-- Navigation Buttons --}}
                    <div class="mt-12 flex justify-between items-center pt-8 border-t border-stone/50" x-show="!formSuccess">
                        <button type="button" x-show="step > 1" @click="step--" class="text-[10px] font-bold uppercase tracking-[0.2em] text-ink/50 hover:text-forest transition-colors flex items-center gap-2">
                            <i data-lucide="arrow-left" class="w-3 h-3"></i> Back
                        </button>
                        <div x-show="step === 1"></div> {{-- Spacer --}}

                        <button type="button" x-show="step < 3" @click="nextStep" class="btn-solid h-12 px-8 text-[10px] tracking-[0.2em] uppercase font-bold rounded-sm ml-auto">
                            Continue
                        </button>
                        
                        <button type="submit" x-show="step === 3" class="btn-solid h-12 px-8 text-[10px] tracking-[0.2em] uppercase font-bold rounded-sm ml-auto" :class="{ 'opacity-50 cursor-not-allowed': !emailVerified }">
                            Submit Request
                        </button>
                    </div>
                </form>

                {{-- Success State --}}
                <div x-show="formSuccess" x-transition.opacity class="absolute inset-0 bg-white z-20 flex flex-col items-center justify-center text-center p-8 lg:p-12">
                    <div class="w-20 h-20 rounded-full border border-forest/20 flex items-center justify-center mb-8">
                        <i data-lucide="check" class="w-8 h-8 text-forest"></i>
                    </div>
                    <h3 class="text-3xl font-serif text-forest mb-4">Request Received.</h3>
                    <p class="text-ink/70 leading-relaxed max-w-sm mb-8" x-text="formMessage"></p>
                    <button @click="resetWizard" class="btn-outline text-[10px] uppercase tracking-[0.2em] font-bold">Submit Another Inquiry</button>
                </div>
            </div>
            @else
                <div class="p-8 text-center text-ink/60 border border-dashed border-stone">
                    [Wizard Block: Please select a valid form in the CMS.]
                </div>
            @endif
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('luxuryWizard', (formSlug) => ({
        step: 1,
        formSlug: formSlug,
        formData: {
            service: '',
            city: '',
            property_type: '',
            project_details: '',
            full_name: '',
            phone: '',
            email: ''
        },
        
        // OTP State
        showVerifyBtn: false,
        showOtpField: false,
        emailVerified: false,
        otpCode: '',
        otpMessage: '',
        
        // Form State
        isSubmitting: false,
        formSuccess: false,
        formMessage: '',

        nextStep() {
            // Simple HTML5 Validation check before proceeding
            let valid = true;
            if (this.step === 1) {
                if (!this.formData.service || !this.formData.city) valid = false;
            } else if (this.step === 2) {
                if (!this.formData.project_details) valid = false;
            }
            
            if (!valid) {
                // Trigger native browser validation UI
                document.getElementById('luxury-wizard-form').reportValidity();
                return;
            }
            this.step++;
            if (window.lenis) {
                // Scroll to top of form smoothly
                window.lenis.scrollTo(document.getElementById('luxury-wizard-form'), { offset: -150 });
            }
        },

        async checkEmail() {
            if (!this.formData.email || !this.formData.email.includes('@')) return;
            this.otpMessage = '';
            try {
                const res = await fetch('/api/v1/otp/check', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify({email: this.formData.email})
                });
                const data = await res.json();
                if (data.requires_verification) {
                    this.showVerifyBtn = true;
                    this.emailVerified = false;
                } else {
                    this.showVerifyBtn = false;
                    this.showOtpField = false;
                    this.emailVerified = true;
                }
            } catch (err) {
                console.error(err);
            }
        },

        async sendOtp() {
            this.showVerifyBtn = false;
            this.otpMessage = 'Sending code...';
            try {
                const res = await fetch('/api/v1/otp/send', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify({email: this.formData.email})
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || res.statusText);
                this.otpMessage = data.message;
                this.showOtpField = true;
            } catch (err) {
                this.otpMessage = err.message || 'Failed to send code. Please try again.';
                this.showVerifyBtn = true;
            }
        },

        async verifyOtp() {
            this.otpMessage = 'Verifying...';
            try {
                const res = await fetch('/api/v1/otp/verify', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify({email: this.formData.email, otp: this.otpCode})
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || res.statusText);
                
                if (data.success) {
                    this.emailVerified = true;
                    this.showOtpField = false;
                    this.otpMessage = '';
                } else {
                    this.otpMessage = data.message;
                }
            } catch (err) {
                this.otpMessage = err.message || 'Verification failed. Please try again.';
            }
        },

        async submitWizard() {
            if (!this.emailVerified) {
                this.formMessage = 'Please verify your email address before submitting.';
                return;
            }
            
            this.isSubmitting = true;
            this.formMessage = '';
            
            try {
                const res = await fetch(`/api/v1/forms/${this.formSlug}/submit`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? ''},
                    body: JSON.stringify(this.formData)
                });
                const result = await res.json();
                if (!res.ok) throw new Error(result.message || res.statusText);
                
                this.formSuccess = result.success;
                this.formMessage = result.message || 'Your submission has been received. Our concierge will contact you shortly.';
            } catch (err) {
                this.formSuccess = false;
                this.formMessage = err.message || 'Something went wrong. Please try again or call us directly.';
            } finally {
                this.isSubmitting = false;
            }
        },
        
        resetWizard() {
            this.step = 1;
            this.formSuccess = false;
            this.emailVerified = false;
            this.showVerifyBtn = false;
            this.showOtpField = false;
            this.otpCode = '';
            this.formData = {
                service: '', city: '', property_type: '', project_details: '', full_name: '', phone: '', email: ''
            };
        }
    }));
});
</script>
@endpush