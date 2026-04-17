@props(['field', 'context' => '', 'pageContext' => ''])
@if(\App\Services\AiContentService::isAvailable())
<div class="relative inline-block" x-data="aiGenerate('{{ $field }}', '{{ addslashes($context) }}', '{{ addslashes($pageContext) }}')">
    <button type="button"
            x-on:click="toggle()"
            class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-forest hover:text-forest-light transition rounded-lg hover:bg-forest-50"
            title="Generate with AI">
        <i data-lucide="bot" class="w-3.5 h-3.5"></i>
        <span>AI</span>
    </button>

    <div x-show="open" x-cloak x-transition
         x-on:click.outside="open = false"
         class="absolute right-0 top-full mt-1 z-50 w-80 max-w-[calc(100vw-2rem)] bg-white rounded-xl border border-gray-200 shadow-xl p-4 sm:w-96">
        <div class="mb-3 flex items-start justify-between gap-3">
            <h4 class="text-sm font-bold text-text flex items-center gap-1.5">
                <i data-lucide="bot" class="w-4 h-4 text-forest"></i> AI Content Generator
            </h4>
            <button type="button" x-on:click="open = false" class="text-text-secondary hover:text-text">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <template x-if="!result && !loading">
            <div class="space-y-3">
                <textarea x-model="customInstructions" rows="2" placeholder="Optional: custom instructions for generation..."
                          class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition"></textarea>
                <button type="button" x-on:click="generateContent()"
                        class="w-full bg-forest hover:bg-forest-light text-white text-sm font-medium py-2 rounded-lg transition flex items-center justify-center gap-2">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Generate Content
                </button>
            </div>
        </template>

        <template x-if="loading">
            <div class="py-6 text-center">
                <div class="animate-spin w-6 h-6 border-2 border-forest border-t-transparent rounded-full mx-auto mb-2"></div>
                <p class="text-xs text-text-secondary">Generating content...</p>
            </div>
        </template>

        <template x-if="result && !loading">
            <div class="space-y-3">
                <div class="bg-gray-50 rounded-lg p-3 max-h-48 overflow-y-auto">
                    <p class="text-xs text-text whitespace-pre-wrap" x-text="result"></p>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <button type="button" x-on:click="acceptContent()"
                            class="flex-1 bg-forest hover:bg-forest-light text-white text-xs font-medium py-2 rounded-lg transition flex items-center justify-center gap-1.5">
                        <i data-lucide="check" class="w-3 h-3"></i> Accept
                    </button>
                    <button type="button" x-on:click="result = null"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-text text-xs font-medium py-2 rounded-lg transition flex items-center justify-center gap-1.5">
                        <i data-lucide="refresh-cw" class="w-3 h-3"></i> Regenerate
                    </button>
                </div>
                <div x-show="errorMsg" x-cloak class="text-xs text-red-600" x-text="errorMsg"></div>
            </div>
        </template>

        <div x-show="errorMsg && !result && !loading" x-cloak class="mt-2 text-xs text-red-600" x-text="errorMsg"></div>
    </div>
</div>
@endif
