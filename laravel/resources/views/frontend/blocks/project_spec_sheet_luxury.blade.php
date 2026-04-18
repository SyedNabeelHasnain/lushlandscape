@php
    $project = $context['project'] ?? null;
    $description = $project ? $project->description : 'Detailed project description goes here.';
    $challenge = $project ? $project->challenge : 'Project challenges and constraints.';
    $solution = $project ? $project->solution : 'The executed solution and design intent.';
    $materials = $project ? $project->materials : [];
    $service = $project && $project->service ? $project->service->name : 'N/A';
    $completion = $project && $project->completion_date ? \Carbon\Carbon::parse($project->completion_date)->format('F Y') : 'N/A';
@endphp
<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy">
    <div class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-16 lg:gap-24 gs-reveal">
        
        {{-- Left: Specs --}}
        <div class="w-full lg:w-1/3">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-8 lg:mb-12 border-b border-stone pb-4">{{ $content['eyebrow'] ?? 'Project Overview' }}</p>
            
            <dl class="space-y-6 lg:space-y-8">
                <div>
                    <dt class="text-[9px] uppercase tracking-[0.2em] font-semibold text-ink/40 mb-2">Service Execution</dt>
                    <dd class="text-base lg:text-lg font-serif text-forest">{{ $service }}</dd>
                </div>
                <div>
                    <dt class="text-[9px] uppercase tracking-[0.2em] font-semibold text-ink/40 mb-2">Completion</dt>
                    <dd class="text-base lg:text-lg font-serif text-forest">{{ $completion }}</dd>
                </div>
                @if($materials && count($materials) > 0)
                <div>
                    <dt class="text-[9px] uppercase tracking-[0.2em] font-semibold text-ink/40 mb-2">Primary Materials</dt>
                    <dd class="space-y-1">
                        @foreach($materials as $mat)
                        <div class="text-sm lg:text-base font-light text-ink/80 flex items-center gap-2">
                            <span class="w-1 h-1 bg-accent rounded-full"></span> {{ $mat }}
                        </div>
                        @endforeach
                    </dd>
                </div>
                @endif
            </dl>
        </div>

        {{-- Right: Description --}}
        <div class="w-full lg:w-2/3 prose prose-stone lg:prose-lg max-w-none text-ink/80 font-light leading-relaxed font-sans">
            @if($description)
            <div class="mb-10 lg:mb-12">
                <h3 class="text-2xl font-serif text-forest mb-4 lg:mb-6">The Vision</h3>
                {!! nl2br(e($description)) !!}
            </div>
            @endif
            
            @if($challenge && $solution)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 border-t border-stone pt-10 lg:pt-12">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4">The Challenge</h4>
                    <p class="text-sm lg:text-base leading-[1.8]">{{ $challenge }}</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-forest mb-4">The Solution</h4>
                    <p class="text-sm lg:text-base leading-[1.8]">{{ $solution }}</p>
                </div>
            </div>
            @endif
        </div>

    </div>
</section>