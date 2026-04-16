{{-- Block: split_consultation_panel --}}
@php
    $eyebrow = $content['eyebrow'] ?? 'Get Started';
    $heading = $content['heading'] ?? 'Book a Consultation';
    $editorialCopy = $content['editorial_copy'] ?? '';
    $trustLines = array_map('trim', explode(',', $content['trust_lines'] ?? ''));
    $tone = $content['tone'] ?? 'dark';
    $mediaId = $content['media_id'] ?? null;

    $asset = $mediaId ? ($mediaLookup[$mediaId] ?? null) : null;
    $mediaUrl = $asset ? $asset->url : null;

    $toneClasses = match($tone) {
        'light' => 'bg-white text-ink border-stone',
        'forest' => 'bg-forest text-white border-forest-light',
        default => 'bg-ink text-white border-white/10' // dark
    };
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
                <h2 class="text-display font-heading font-bold mb-8 leading-tight">{{ $heading }}</h2>
                <div class="w-16 h-px bg-current opacity-30 mb-8"></div>
                <p class="text-xl font-light opacity-90 leading-relaxed max-w-lg">{{ $editorialCopy }}</p>
            </div>
            
            <div class="relative z-10 mt-16 pt-8 border-t border-current/10">
                <ul class="space-y-4">
                    @foreach($trustLines as $line)
                        <li class="flex items-center gap-4 text-sm uppercase tracking-[0.15em] font-semibold opacity-80">
                            <i data-lucide="check-circle-2" class="w-5 h-5 opacity-60"></i>
                            {{ $line }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Right Panel: Form --}}
        <div class="w-full lg:w-7/12 xl:w-1/2 bg-white text-ink p-10 lg:p-20 relative">
            <div class="absolute inset-0 bg-stone/5 pointer-events-none"></div>
            <div class="relative z-10 max-w-lg mx-auto">
                <h3 class="text-2xl font-bold font-heading mb-2">Request Your Quote</h3>
                <p class="text-text-secondary text-sm mb-10">Fill out the form below and our design team will get back to you within 24 hours.</p>
                
                {{-- In a real scenario, this form would map to the form_block component or a specific Livewire/Live form. 
                     For Phase B structural setup, we mock the premium floating label layout. --}}
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative group">
                            <input type="text" id="fname" class="w-full bg-transparent border-b border-stone py-4 text-ink focus:border-forest focus:outline-none transition-colors peer" placeholder=" ">
                            <label for="fname" class="absolute left-0 top-4 text-text-secondary transition-all peer-focus:-top-3 peer-focus:text-xs peer-focus:text-forest peer-[:not(:placeholder-shown)]:-top-3 peer-[:not(:placeholder-shown)]:text-xs">First Name</label>
                        </div>
                        <div class="relative group">
                            <input type="text" id="lname" class="w-full bg-transparent border-b border-stone py-4 text-ink focus:border-forest focus:outline-none transition-colors peer" placeholder=" ">
                            <label for="lname" class="absolute left-0 top-4 text-text-secondary transition-all peer-focus:-top-3 peer-focus:text-xs peer-focus:text-forest peer-[:not(:placeholder-shown)]:-top-3 peer-[:not(:placeholder-shown)]:text-xs">Last Name</label>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <input type="email" id="email" class="w-full bg-transparent border-b border-stone py-4 text-ink focus:border-forest focus:outline-none transition-colors peer" placeholder=" ">
                        <label for="email" class="absolute left-0 top-4 text-text-secondary transition-all peer-focus:-top-3 peer-focus:text-xs peer-focus:text-forest peer-[:not(:placeholder-shown)]:-top-3 peer-[:not(:placeholder-shown)]:text-xs">Email Address</label>
                    </div>

                    <div class="relative group">
                        <input type="tel" id="phone" class="w-full bg-transparent border-b border-stone py-4 text-ink focus:border-forest focus:outline-none transition-colors peer" placeholder=" ">
                        <label for="phone" class="absolute left-0 top-4 text-text-secondary transition-all peer-focus:-top-3 peer-focus:text-xs peer-focus:text-forest peer-[:not(:placeholder-shown)]:-top-3 peer-[:not(:placeholder-shown)]:text-xs">Phone Number</label>
                    </div>

                    <div class="relative group pt-4">
                        <textarea id="message" rows="3" class="w-full bg-transparent border-b border-stone py-4 text-ink focus:border-forest focus:outline-none transition-colors peer resize-none" placeholder=" "></textarea>
                        <label for="message" class="absolute left-0 top-8 text-text-secondary transition-all peer-focus:-top-3 peer-focus:text-xs peer-focus:text-forest peer-[:not(:placeholder-shown)]:-top-3 peer-[:not(:placeholder-shown)]:text-xs">Project Details</label>
                    </div>

                    <button type="button" class="w-full btn-luxury bg-forest text-white border-forest hover:bg-ink hover:border-ink mt-8 py-5 text-sm uppercase tracking-widest font-bold">
                        Submit Request
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>