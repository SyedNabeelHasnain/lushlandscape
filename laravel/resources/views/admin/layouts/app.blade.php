<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin | Super WMS</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>

<body class="bg-gray-50 min-h-screen overflow-x-hidden" x-data="{ sidebarOpen: false }" :class="sidebarOpen ? 'overflow-hidden lg:overflow-x-hidden' : ''">

            @php
        // Pre-compute which groups are active so sidebar auto-opens the right group
        $g = [
            'faqs' => request()->routeIs('admin.faqs.*'),
            'reviews' => request()->routeIs('admin.reviews.*'),
            'content' => request()->routeIs('admin.popups.*', 'admin.home-page.*'),
            'forms' => request()->routeIs('admin.forms.*', 'admin.submissions.*'),
            'system' => request()->routeIs('admin.redirects.*', 'admin.security-rules.*', 'admin.webhooks.*'),
        ];
    @endphp

    {{-- ── Sidebar ─────────────────────────────────────────────────────────────── --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw] flex-col bg-sidebar shadow-2xl transform transition-transform duration-200 lg:translate-x-0 lg:shadow-none"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="flex items-center h-16 px-6 border-b border-forest-dark/30 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-forest-light rounded-lg flex items-center justify-center shrink-0">
                    <span class="text-white font-bold text-sm">L</span>
                </div>
                <div>
                    <span class="text-white font-semibold text-sm block leading-tight">Super WMS</span>
                    <span class="text-white/40 text-xs">Content Management</span>
                </div>
            </div>
        </div>

        {{-- Scrollable Navigation --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5" x-data="{ g: @js($g) }">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 shrink-0"></i>
                Dashboard
            </a>

            <!-- Core Super WMS Architecture -->
            <div class="pt-3 pb-1.5 px-3">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider">Super WMS Core</p>
            </div>

            <a href="{{ route('admin.content-types.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.content-types.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="layers" class="w-4 h-4 shrink-0"></i>
                Content Types
            </a>

            @php
                $navContentTypes = \App\Models\ContentType::orderBy('name')->get();
            @endphp
            @foreach($navContentTypes as $ct)
            <a href="{{ route('admin.entries.index', ['type' => $ct->id]) }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.entries.*') && request('type') == $ct->id ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="{{ $ct->icon ?? 'file-text' }}" class="w-4 h-4 shrink-0"></i>
                {{ Str::plural($ct->name) }}
            </a>
            @endforeach

            <!-- Global Taxonomies (Placeholder for Future) -->
            <div class="pt-3 pb-1.5 px-3">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider">Taxonomies</p>
            </div>

            <a href="{{ route('admin.taxonomies.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.taxonomies.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="tags" class="w-4 h-4 shrink-0"></i>
                Taxonomy Manager
            </a>

            <div class="pt-3 pb-1.5 px-3">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider">Legacy Modules</p>
            </div>

            {{-- Services Group (Categories, Services, Cities, City Pages, Matrix) --}}
            {{-- Removed Legacy Routing --}}

            {{-- Blog Group --}}
            {{-- Removed Legacy Routing --}}

            {{-- Portfolio Group --}}
            {{-- Removed Legacy Routing --}}

            {{-- FAQs Group --}}
            <div>
                <button type="button" x-on:click="g.faqs = !g.faqs"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition"
                    :class="g.faqs ? 'text-white bg-sidebar-hover' : 'text-white/75 hover:text-white hover:bg-sidebar-hover'">
                    <i data-lucide="help-circle" class="w-4 h-4 shrink-0"></i>
                    <span class="flex-1 text-left font-medium">FAQs</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                        :class="g.faqs ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="g.faqs" x-cloak x-collapse class="mt-0.5 ml-3 pl-3 border-l border-white/10 space-y-0.5">
                    <a href="{{ route('admin.faqs.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.faqs.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="message-circle-question" class="w-3.5 h-3.5 shrink-0"></i>
                        All FAQs
                    </a>
                </div>
            </div>

            {{-- Reviews Group --}}
            <div>
                <button type="button" x-on:click="g.reviews = !g.reviews"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition"
                    :class="g.reviews ? 'text-white bg-sidebar-hover' : 'text-white/75 hover:text-white hover:bg-sidebar-hover'">
                    <i data-lucide="star" class="w-4 h-4 shrink-0"></i>
                    <span class="flex-1 text-left font-medium">Reviews</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                        :class="g.reviews ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="g.reviews" x-cloak x-collapse class="mt-0.5 ml-3 pl-3 border-l border-white/10 space-y-0.5">
                    <a href="{{ route('admin.reviews.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.reviews.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="thumbs-up" class="w-3.5 h-3.5 shrink-0"></i>
                        All Reviews
                    </a>
                </div>
            </div>

            {{-- Content Group (Static Pages + Popups) --}}
            <div>
                <button type="button" x-on:click="g.content = !g.content"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition"
                    :class="g.content ? 'text-white bg-sidebar-hover' : 'text-white/75 hover:text-white hover:bg-sidebar-hover'">
                    <i data-lucide="file-stack" class="w-4 h-4 shrink-0"></i>
                    <span class="flex-1 text-left font-medium">Pages</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                        :class="g.content ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="g.content" x-cloak x-collapse class="mt-0.5 ml-3 pl-3 border-l border-white/10 space-y-0.5">
                    <a href="{{ route('admin.home-page.edit') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.home-page.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="home" class="w-3.5 h-3.5 shrink-0"></i>
                        Home Page
                    </a>
                    <a href="{{ route('admin.page-builders.edit', ['page' => 'services-hub']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.page-builders.*') && request()->route('page') === 'services-hub' ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="briefcase-business" class="w-3.5 h-3.5 shrink-0"></i>
                        Services Hub
                    </a>
                    <a href="{{ route('admin.page-builders.edit', ['page' => 'locations-hub']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.page-builders.*') && request()->route('page') === 'locations-hub' ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="map" class="w-3.5 h-3.5 shrink-0"></i>
                        Locations Hub
                    </a>
                    <a href="{{ route('admin.page-builders.edit', ['page' => 'portfolio-index']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.page-builders.*') && request()->route('page') === 'portfolio-index' ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="images" class="w-3.5 h-3.5 shrink-0"></i>
                        Portfolio Index
                    </a>
                    <a href="{{ route('admin.page-builders.edit', ['page' => 'blog-index']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.page-builders.*') && request()->route('page') === 'blog-index' ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="newspaper" class="w-3.5 h-3.5 shrink-0"></i>
                        Blog Index
                    </a>
                    <a href="{{ route('admin.page-builders.edit', ['page' => 'faqs-index']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.page-builders.*') && request()->route('page') === 'faqs-index' ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="help-circle" class="w-3.5 h-3.5 shrink-0"></i>
                        FAQ Index
                    </a>
                    <a href="{{ route('admin.page-builders.edit', ['page' => 'contact']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.page-builders.*') && request()->route('page') === 'contact' ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="phone" class="w-3.5 h-3.5 shrink-0"></i>
                        Contact Page
                    </a>
                    <a href="{{ route('admin.page-builders.edit', ['page' => 'consultation']) }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.page-builders.*') && request()->route('page') === 'consultation' ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="clipboard-signature" class="w-3.5 h-3.5 shrink-0"></i>
                        Consultation Page
                    </a>
                    <a href="{{ route('admin.popups.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.popups.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="layout-panel-top" class="w-3.5 h-3.5 shrink-0"></i>
                        Popups
                    </a>
                    <a href="{{ route('admin.card-templates.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.card-templates.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="layout" class="w-3.5 h-3.5 shrink-0"></i>
                        Card Templates
                    </a>
                    <a href="{{ route('admin.theme-layouts.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.theme-layouts.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="layout-template" class="w-3.5 h-3.5 shrink-0"></i>
                        Site Builder
                    </a>
                </div>
            </div>

            <div class="pt-3 pb-1.5 px-3">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider">Leads & Assets</p>
            </div>

            {{-- Forms Group --}}
            <div>
                <button type="button" x-on:click="g.forms = !g.forms"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition"
                    :class="g.forms ? 'text-white bg-sidebar-hover' : 'text-white/75 hover:text-white hover:bg-sidebar-hover'">
                    <i data-lucide="clipboard-list" class="w-4 h-4 shrink-0"></i>
                    <span class="flex-1 text-left font-medium">Forms</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                        :class="g.forms ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="g.forms" x-cloak x-collapse class="mt-0.5 ml-3 pl-3 border-l border-white/10 space-y-0.5">
                    <a href="{{ route('admin.forms.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.forms.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="form-input" class="w-3.5 h-3.5 shrink-0"></i>
                        Form Builder
                    </a>
                    <a href="{{ route('admin.submissions.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.submissions.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="inbox" class="w-3.5 h-3.5 shrink-0"></i>
                        Submissions
                    </a>
                </div>
            </div>

            {{-- Media Library --}}
            <a href="{{ route('admin.media.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.media.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="image" class="w-4 h-4 shrink-0"></i>
                Media Library
            </a>

            {{-- Bulk Media Import --}}
            <a href="{{ route('admin.bulk-import.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.bulk-import.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="download-cloud" class="w-4 h-4 shrink-0"></i>
                Bulk Import
            </a>

            {{-- Search Analytics --}}
            <a href="{{ route('admin.search-analytics') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.search-analytics') ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="bar-chart-2" class="w-4 h-4 shrink-0"></i>
                Search Analytics
            </a>

            <div class="pt-3 pb-1.5 px-3">
                <p class="text-xs font-semibold text-white/30 uppercase tracking-wider">System</p>
            </div>

            {{-- Settings --}}
            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition {{ request()->routeIs('admin.settings.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/75 hover:text-white hover:bg-sidebar-hover' }}">
                <i data-lucide="settings" class="w-4 h-4 shrink-0"></i>
                Settings
            </a>

            {{-- System Group (Redirects + Security) --}}
            <div>
                <button type="button" x-on:click="g.system = !g.system"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition"
                    :class="g.system ? 'text-white bg-sidebar-hover' : 'text-white/75 hover:text-white hover:bg-sidebar-hover'">
                    <i data-lucide="shield" class="w-4 h-4 shrink-0"></i>
                    <span class="flex-1 text-left font-medium">Advanced</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 shrink-0 transition-transform duration-200"
                        :class="g.system ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="g.system" x-cloak x-collapse class="mt-0.5 ml-3 pl-3 border-l border-white/10 space-y-0.5">
                    <a href="{{ route('admin.redirects.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.redirects.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="arrow-right-left" class="w-3.5 h-3.5 shrink-0"></i>
                        Redirects
                    </a>
                    <a href="{{ route('admin.security-rules.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.security-rules.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="shield-alert" class="w-3.5 h-3.5 shrink-0"></i>
                        Security Rules
                    </a>
                    <a href="{{ route('admin.webhooks.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.webhooks.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="webhook" class="w-3.5 h-3.5 shrink-0"></i>
                        Webhooks
                    </a>
                    <a href="{{ route('admin.import-export.index') }}"
                        class="flex items-center gap-2.5 px-3 py-2 text-xs rounded-lg transition {{ request()->routeIs('admin.import-export.*') ? 'bg-sidebar-active text-white font-medium' : 'text-white/65 hover:text-white hover:bg-sidebar-hover' }}">
                        <i data-lucide="arrow-left-right" class="w-3.5 h-3.5 shrink-0"></i>
                        Import / Export
                    </a>
                </div>
            </div>

            {{-- Bottom padding --}}
            <div class="h-4"></div>
        </nav>

        {{-- Admin user footer strip --}}
        <div class="shrink-0 px-4 py-3 border-t border-forest-dark/30 flex items-center gap-3">
            <div class="w-8 h-8 bg-forest-light rounded-full flex items-center justify-center shrink-0">
                <span
                    class="text-white text-xs font-bold">{{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-white/40 truncate">{{ auth()->user()->email ?? '' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="text-white/40 hover:text-red-300 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main content wrapper ────────────────────────────────────────────────── --}}
    <div class="min-w-0 lg:pl-72">
        <header class="sticky top-0 z-40 flex min-h-14 flex-wrap items-center gap-3 border-b border-gray-200 bg-white px-4 py-3 sm:px-6"
            x-data="{ confirmClear: false }">
            <button x-on:click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                <i data-lucide="menu" class="w-5 h-5 text-text"></i>
            </button>
            <div class="ml-auto flex max-w-full flex-wrap items-center justify-end gap-2 sm:gap-3">
                <a href="{{ route('home') }}" target="_blank"
                    data-tippy-content="Open the public-facing website in a new browser tab to preview live changes."
                    aria-label="View Site"
                    class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-xs text-text-secondary hover:bg-gray-50 hover:text-forest transition">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    <span class="hidden sm:inline">View Site</span>
                </a>
                <a href="{{ route('admin.search-analytics') }}"
                    data-tippy-content="See what visitors are searching for on the site. Helps identify content gaps and popular topics."
                    aria-label="Search Analytics"
                    class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-xs text-text-secondary hover:bg-gray-50 hover:text-forest transition">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="hidden md:inline">Search Analytics</span>
                </a>

                {{-- Clear Cache button --}}
                <button type="button" x-on:click="confirmClear = true"
                    data-tippy-content="Flush all server caches (config, routes, views, app cache), delete the sitemap, wipe all sessions, and clear browser storage. You will be logged out after clearing."
                    aria-label="Clear Cache"
                    class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-2 text-xs text-text-secondary hover:bg-amber-50 hover:text-amber-600 transition">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    <span class="hidden md:inline">Clear Cache</span>
                </button>
            </div>

            {{-- Confirmation modal --}}
            <div x-show="confirmClear" x-cloak x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
                x-on:click.self="confirmClear = false">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-2xl p-6 w-full max-w-md mx-4"
                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-text mb-1">Clear All Caches?</h3>
                            <p class="text-xs text-text-secondary leading-relaxed">
                                This will flush <strong>all server caches</strong> (config, routes, views, application),
                                delete the sitemap, wipe all active sessions, and clear your browser's local storage,
                                session storage, and cookies. <strong>You will be logged out.</strong>
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl px-4 py-3 mb-5 space-y-1">
                        <p class="text-xs text-text-secondary flex items-center gap-2">
                            <i data-lucide="server" class="w-3 h-3 text-forest shrink-0"></i>
                            Server: config, route, view, app cache + sitemap + all sessions
                        </p>
                        <p class="text-xs text-text-secondary flex items-center gap-2">
                            <i data-lucide="monitor" class="w-3 h-3 text-forest shrink-0"></i>
                            Client: localStorage, sessionStorage, all browser cookies
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" x-on:click="confirmClear = false"
                            class="px-4 py-2 text-xs rounded-xl border border-gray-200 text-text-secondary hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <form method="POST" action="{{ route('admin.clear-cache') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-1.5 px-4 py-2 text-xs rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                Yes, Clear Everything
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        <main class="p-4 sm:p-6">
            @yield('content')
        </main>
    </div>

    {{-- Mobile sidebar overlay --}}
    <div x-show="sidebarOpen" x-cloak x-on:click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    @stack('scripts')
</body>

</html>
