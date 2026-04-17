@props(['title' => 'On This Page'])
<div
    x-data="{ headings: [], open: true }"
    x-init="
        const els = document.querySelectorAll('.prose h2, .prose h3');
        els.forEach((el, i) => {
            if (!el.id) el.id = 'section-' + i;
            headings.push({ id: el.id, text: el.textContent.trim(), level: el.tagName });
        });
    "
    x-show="headings.length > 1"
    class="bg-cream border border-stone p-8 mb-10"
>
    <button x-on:click="open=!open" class="flex items-center justify-between w-full text-left">
        <span class="text-eyebrow text-ink">{{ $title }}</span>
        <i data-lucide="chevron-down" class="w-4 h-4 text-text-secondary transition-transform duration-200" :class="{'rotate-180': !open}"></i>
    </button>
    <nav x-show="open" x-cloak x-collapse class="mt-4" aria-label="Table of contents">
        <ul class="space-y-2">
            <template x-for="h in headings" :key="h.id">
                <li>
                    <a :href="'#' + h.id"
                       class="flex items-center gap-2 text-sm text-text-secondary hover:text-forest transition-colors"
                       :class="{ 'pl-4': h.level === 'H3' }">
                        <span x-show="h.level === ' x-cloakH2'" class="w-1 h-1 bg-forest shrink-0"></span>
                        <span x-show="h.level === ' x-cloakH3'" class="w-1 h-1 bg-stone shrink-0"></span>
                        <span x-text="h.text"></span>
                    </a>
                </li>
            </template>
        </ul>
    </nav>
</div>
