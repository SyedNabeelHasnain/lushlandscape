{{-- Block: split_consultation_panel --}}
@php
    $eyebrow = $content['eyebrow'] ?? 'Get Started';
    $heading = $content['heading'] ?? 'Book a Consultation';
    $editorialCopy = $content['editorial_copy'] ?? '';
    $trustLines = array_map('trim', explode(',', $content['trust_lines'] ?? ''));
    $tone = $content['tone'] ?? 'dark';
    $mediaId = $content['media_id'] ?? null;
    $formSlug = $content['form_slug'] ?? 'contact-us';

    $asset = $mediaId ? ($mediaLookup[$mediaId] ?? null) : null;
    $mediaUrl = $asset ? $asset->url : null;

    $toneClasses = match($tone) {
        'light' => 'bg-white text-ink border-stone',
        'forest' => 'bg-forest text-white border-forest-light',
        default => 'bg-ink text-white border-white/10' // dark
    };
    
    // Fetch the actual form from the database
    $form = \App\Models\Form::where('slug', $formSlug)->where('is_active', true)->with('fields')->first();
    $formId = 'split-form-' . uniqid();
@endphp

<div class="max-w-[1440px] mx-auto px-4 md:px-8 lg:px-12 py-12 lg:py-24">
    <div class="rounded-[2.5rem] lg:rounded-[3.5rem] overflow-hidden flex flex-col lg:flex-row shadow-luxury-lg {{ $toneClasses }} animate-on-scroll" data-animation="fade-up">
        
        {{-- Left Panel: Editorial & Image --}}
        <div class="w-full lg:w-5/12 xl:w-1/2 relative p-12 lg:p-20 flex flex-col justify-between overflow-hidden">
            @if($mediaUrl)
                <img src="{{ $mediaUrl }}" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay pointer-events-none" loading="lazy">
            @endif
            
            <div class="relative z-10">
                <span class="text-luxury-label opacity-70 block mb-6">{{ $eyebrow }}</span>
                <h2 class="text-display font-heading font-bold mb-8 leading-tight text-balance">{!! $heading !!}</h2>
                <div class="w-16 h-px bg-current opacity-30 mb-8"></div>
                <p class="text-xl font-light opacity-90 leading-relaxed max-w-lg">{!! $editorialCopy !!}</p>
            </div>
            
            <div class="relative z-10 mt-16 pt-8 border-t border-current/10">
                <ul class="space-y-4">
                    @foreach($trustLines as $line)
                        @if(!empty($line))
                        <li class="flex items-center gap-4 text-sm uppercase tracking-[0.15em] font-semibold opacity-80">
                            <i data-lucide="check-circle-2" class="w-5 h-5 opacity-60"></i>
                            {{ $line }}
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Right Panel: Form --}}
        <div class="w-full lg:w-7/12 xl:w-1/2 bg-white text-ink p-10 lg:p-20 relative">
            <div class="absolute inset-0 bg-stone/5 pointer-events-none"></div>
            <div class="relative z-10 max-w-lg mx-auto">
                <div class="mb-10">
                    <h3 class="text-2xl font-bold font-heading mb-2">Book Your Consultation</h3>
                    <p class="text-text-secondary text-sm">Provide your details and our team will be in touch shortly.</p>
                </div>
                
                @if($form)
                    <div x-data="contactForm('{{ $formId }}', '{{ $form->slug }}')" class="relative">
                            @include('frontend.blocks.partials._form-fields', [
                                'form' => $form,
                                'formId' => $formId,
                                'variant' => 'split',
                                'tone' => 'light',
                                'labelClass' => 'text-text-secondary text-sm font-semibold mb-2 block',
                                'fieldToneClass' => 'bg-transparent border-b border-stone py-4 text-ink focus:border-forest focus:outline-none transition-colors rounded-none',
                                'fieldStyleClass' => 'field-luxury-underline',
                                'fieldColumns' => '2',
                                'buttonClass' => 'w-full btn-luxury bg-forest text-white border-forest hover:bg-ink hover:border-ink mt-8 py-5 text-sm uppercase tracking-widest font-bold disabled:opacity-50 disabled:cursor-not-allowed',
                                'submitText' => 'Book Consultation',
                            ])
                        
                        {{-- Success State --}}
                        <div x-show="isSuccess" x-transition.opacity class="absolute inset-0 bg-white/95 backdrop-blur-sm z-20 flex flex-col items-center justify-center text-center p-8">
                            <div class="w-16 h-16 bg-forest/10 rounded-full flex items-center justify-center text-forest mb-6">
                                <i data-lucide="check" class="w-8 h-8"></i>
                            </div>
                            <h4 class="text-2xl font-bold font-heading text-ink mb-2">Request Received</h4>
                            <p class="text-text-secondary">Thank you. Our team will review your details and contact you shortly to schedule your consultation.</p>
                        </div>
                    </div>
                @else
                    <div class="p-8 border border-dashed border-stone rounded-xl text-center">
                        <p class="text-text-secondary text-sm">Consultation form unavailable. Please configure a valid form slug.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>