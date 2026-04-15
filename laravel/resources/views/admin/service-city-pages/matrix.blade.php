@extends('admin.layouts.app')
@section('title', 'Service-City Setup')
@section('content')
<x-admin.flash-message />
<x-admin.page-header
    title="Service-City Setup"
    subtitle="Select a city to manage which service pages exist and are active."
>
    <a href="{{ route('admin.service-city-pages.index') }}"
       class="flex items-center gap-1.5 px-4 py-2 border border-gray-200 text-sm rounded-xl text-text-secondary hover:bg-gray-50 transition">
        <i data-lucide="list" class="w-4 h-4"></i>List View
    </a>
</x-admin.page-header>

<div class="flex flex-col xl:flex-row gap-6 items-start">

    {{-- ── Left: City Sidebar ──────────────────────────────────────────────── --}}
    <div class="w-full xl:w-72 xl:shrink-0">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                <p class="text-xs font-semibold text-text-secondary uppercase tracking-wide">Cities</p>
                <p class="text-xs text-text-secondary mt-0.5">{{ $cities->count() }} cities · {{ $totalServices }} services each</p>
            </div>
            <div class="divide-y divide-gray-50 max-h-[420px] overflow-y-auto xl:max-h-[calc(100vh-220px)]">
                @forelse($cities as $city)
                @php
                    $pct = $totalServices > 0 ? round(($city->active_pages / $totalServices) * 100) : 0;
                    $isSelected = $selectedCity?->id === $city->id;
                @endphp
                <a href="{{ route('admin.service-city-matrix', ['city_id' => $city->id]) }}"
                   class="flex flex-col gap-1.5 px-4 py-3 transition border-l-2 {{ $isSelected ? 'bg-forest-50 border-forest' : 'hover:bg-gray-50 border-transparent' }}">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-medium {{ $isSelected ? 'text-forest' : 'text-text' }} truncate">
                            {{ $city->name }}
                        </span>
                        <span class="text-xs shrink-0 {{ $city->active_pages > 0 ? 'text-forest font-medium' : 'text-text-secondary' }}">
                            {{ $city->active_pages }}/{{ $totalServices }}
                        </span>
                    </div>
                    <div class="h-1 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full {{ $pct === 100 ? 'bg-forest' : ($pct > 0 ? 'bg-forest/60' : 'bg-gray-200') }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </a>
                @empty
                <p class="px-4 py-8 text-center text-sm text-text-secondary">No cities found.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Right: Service Setup Panel ──────────────────────────────────────── --}}
    <div class="flex-1 min-w-0">
        @if($selectedCity)

        {{-- City header + quick actions --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-4 py-4 mb-4 flex items-center justify-between gap-4 flex-wrap sm:px-6">
            <div>
                <h2 class="text-base font-bold text-text flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-forest"></i>
                    {{ $selectedCity->name }}
                </h2>
                <p class="text-xs text-text-secondary mt-0.5">
                    {{ $selectedCity->active_pages }} active ·
                    {{ $selectedCity->total_pages }} created ·
                    {{ $totalServices }} total services
                </p>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:flex-wrap">
                <form method="POST" action="{{ route('admin.service-city-pages.generate') }}" class="w-full sm:w-auto">
                    @csrf
                    <input type="hidden" name="city_id" value="{{ $selectedCity->id }}">
                    <button type="submit"
                            class="flex w-full items-center justify-center gap-1.5 px-4 py-2 border border-gray-200 text-sm rounded-xl text-text-secondary hover:bg-gray-50 transition sm:w-auto">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i>Generate All Drafts
                    </button>
                </form>
                <a href="{{ route('admin.cities.edit', $selectedCity) }}"
                   class="flex w-full items-center justify-center gap-1.5 px-4 py-2 border border-gray-200 text-sm rounded-xl text-text-secondary hover:bg-gray-50 transition sm:w-auto">
                    <i data-lucide="settings" class="w-4 h-4"></i>City Settings
                </a>
            </div>
        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-5 text-xs text-text-secondary mb-4 flex-wrap">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-forest inline-block"></span>Active
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>Inactive (page exists)
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span>Not created
            </div>
            <span class="text-text-secondary/70">Checked = activate on save · Unchecked = deactivate on save</span>
        </div>

        {{-- Service checklist form --}}
        <form method="POST" action="{{ route('admin.service-city-pages.bulk-update') }}" data-ajax-form="true" data-success-message="Service pages updated.">
            @csrf
            <input type="hidden" name="city_id" value="{{ $selectedCity->id }}">

            <div class="space-y-4">
                @foreach($categories as $category)
                @if($category->services->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2.5">
                        <i data-lucide="{{ $category->icon ?? 'layers' }}" class="w-4 h-4 text-forest"></i>
                        <span class="text-sm font-semibold text-text">{{ $category->name }}</span>
                        @php
                            $catActive = $category->services->filter(fn($s) => $cityPages->has($s->id) && $cityPages[$s->id]->is_active)->count();
                        @endphp
                        <span class="ml-auto text-xs {{ $catActive > 0 ? 'text-forest font-medium' : 'text-text-secondary' }}">
                            {{ $catActive }}/{{ $category->services->count() }} active
                        </span>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($category->services as $service)
                        @php
                            $page      = $cityPages->get($service->id);
                            $isActive  = $page?->is_active ?? false;
                            $isCreated = $page !== null;
                        @endphp
                        <label class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition cursor-pointer group">
                            <input type="checkbox"
                                   name="active_service_ids[]"
                                   value="{{ $service->id }}"
                                   {{ $isActive ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest/30 cursor-pointer">

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-text group-hover:text-forest transition truncate">
                                    {{ $service->name }}
                                </p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    @if($isActive)
                                        <span class="w-1.5 h-1.5 rounded-full bg-forest inline-block"></span>
                                        <span class="text-xs text-forest font-medium">Active</span>
                                    @elseif($isCreated)
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                                        <span class="text-xs text-amber-600">Inactive</span>
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300 inline-block"></span>
                                        <span class="text-xs text-text-secondary">Not created</span>
                                    @endif
                                </div>
                            </div>

                            @if($page)
                            <a href="{{ route('admin.service-city-pages.edit', $page->id) }}"
                               x-on:click.stop
                               title="Edit this page"
                               class="shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-forest hover:bg-forest-50 transition opacity-0 group-hover:opacity-100">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                            @else
                            <div class="w-7 h-7 shrink-0"></div>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            {{-- Sticky save bar --}}
            <div class="sticky bottom-4 mt-6 bg-white border border-gray-200 shadow-xl rounded-2xl px-4 py-3 flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                <p class="text-xs text-text-secondary max-w-2xl">
                    <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1 text-forest"></i>
                    Checked = <strong>activate</strong> (create if needed). Unchecked = <strong>deactivate</strong>.
                </p>
                <button type="submit" data-loading-label="Saving…"
                        class="flex w-full items-center justify-center gap-2 bg-forest hover:bg-forest-dark text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition shrink-0 sm:w-auto">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save {{ $selectedCity->name }}
                </button>
            </div>
        </form>

        @else
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm flex flex-col items-center justify-center py-20 text-center">
            <div class="w-14 h-14 rounded-2xl bg-forest-50 flex items-center justify-center mb-4">
                <i data-lucide="map-pin" class="w-7 h-7 text-forest"></i>
            </div>
            <h3 class="text-base font-bold text-text mb-2">Select a City</h3>
            <p class="text-sm text-text-secondary max-w-sm">
                Pick a city from the sidebar to manage which service pages exist and are active for that location.
            </p>
        </div>
        @endif
    </div>

</div>

{{-- Summary footer --}}
@php
    $totalActive  = $cities->sum('active_pages');
    $totalCreated = $cities->sum('total_pages');
    $maxPossible  = $cities->count() * $totalServices;
@endphp
<div class="mt-6 flex flex-wrap gap-6 text-sm text-text-secondary">
    <span><strong class="text-text">{{ $cities->count() }}</strong> cities</span>
    <span><strong class="text-text">{{ $totalServices }}</strong> services</span>
    <span><strong class="text-text">{{ $totalCreated }}</strong> / {{ $maxPossible }} pages created</span>
    <span><strong class="text-forest">{{ $totalActive }}</strong> active</span>
</div>
@endsection
