@php
    $form = $block['data'] ?? null;
@endphp
<section id="contact" class="bg-white py-20 lg:py-32 px-5 lg:px-12 overflow-hidden">
    <div class="max-w-6xl mx-auto contact-panel grid grid-cols-1 lg:grid-cols-2 rounded-sm border border-black/5 gs-reveal shadow-sm">
        
        <div class="contact-side p-8 lg:p-16 text-forest relative border-b lg:border-b-0 lg:border-r border-black/5 flex flex-col justify-between">
            <div class="mb-10 lg:mb-0">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-forest-light/60 mb-4 lg:mb-5">{{ $content['eyebrow'] ?? 'Initiate Project' }}</p>
                <h2 class="text-3xl lg:text-[3.5rem] leading-[1.05] mb-4 lg:mb-6 font-serif">{!! $content['heading'] ?? 'Request a<br>Consultation' !!}</h2>
                @if(!empty($content['description']))
                <p class="text-ink/70 text-base lg:text-lg leading-[1.7] max-w-md font-light">{{ $content['description'] }}</p>
                @endif
            </div>
            
            @if(isset($phone) || isset($email))
            <div class="space-y-4 lg:space-y-6 text-forest">
                @if($phone)
                <div class="flex items-center gap-4 lg:gap-5">
                    <div class="w-8 h-8 lg:w-10 lg:h-10 border border-forest/20 rounded-full flex items-center justify-center"><i class="fa-solid fa-phone text-xs lg:text-sm" aria-hidden="true"></i></div>
                    <span class="text-base lg:text-lg font-medium tracking-wide">{{ $phone }}</span>
                </div>
                @endif
                @if($email)
                <div class="flex items-center gap-4 lg:gap-5">
                    <div class="w-8 h-8 lg:w-10 lg:h-10 border border-forest/20 rounded-full flex items-center justify-center"><i class="fa-solid fa-envelope text-xs lg:text-sm" aria-hidden="true"></i></div>
                    <span class="text-base lg:text-lg font-medium tracking-wide break-all">{{ $email }}</span>
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="bg-white p-6 sm:p-8 lg:p-16 relative">
            @if($form)
            <div x-data="contactForm('fse-consultation-form', '{{ $form->slug }}')" x-cloak>
                <form id="fse-consultation-form" @submit.prevent="submitForm" class="space-y-6 lg:space-y-8" :class="{ 'opacity-50 pointer-events-none': formSubmitting || emailVerified }">
                    @include('frontend.blocks.partials._form-fields', [
                        'form' => $form,
                        'formId' => 'fse-consultation-form',
                    ])

                    <div class="pt-6 lg:pt-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 lg:gap-6 border-t border-stone/30 mt-2 lg:mt-4">
                        <p class="text-[9px] lg:text-[10px] text-ink/50 uppercase tracking-[0.1em] max-w-[200px] leading-relaxed">By submitting, you agree to our Privacy Policy.</p>
                        
                        <div x-show="!emailVerified">
                            <button type="button" 
                                    x-show="showVerifyBtn && !showOtpField" 
                                    @click="sendOtp" 
                                    class="btn-solid h-12 lg:h-14 w-full sm:w-auto px-8 lg:px-10 text-xs tracking-[0.15em] uppercase font-semibold rounded-sm">
                                Verify Email
                            </button>
                        </div>
                        <button type="submit" 
                                x-show="emailVerified" 
                                class="btn-solid h-12 lg:h-14 w-full sm:w-auto px-8 lg:px-10 text-xs tracking-[0.15em] uppercase font-semibold rounded-sm">
                            Request Assessment
                        </button>
                    </div>
                </form>

                {{-- Success State --}}
                <div x-show="formSuccess" x-cloak x-transition.opacity class="absolute inset-0 bg-white/95 backdrop-blur-sm z-20 flex flex-col items-center justify-center text-center p-8">
                    <div class="w-16 h-16 bg-forest rounded-full flex items-center justify-center text-white text-2xl mb-6 shadow-lg">
                        <i data-lucide="check"></i>
                    </div>
                    <h3 class="text-2xl font-serif text-forest mb-3">Request Received</h3>
                    <p class="text-ink/70" x-text="formMessage"></p>
                </div>
            </div>
            @else
                <div class="p-8 text-center text-ink/60 border border-dashed border-stone">
                    [Form Block: Please select a valid form in the CMS.]
                </div>
            @endif
        </div>
    </div>
</section>