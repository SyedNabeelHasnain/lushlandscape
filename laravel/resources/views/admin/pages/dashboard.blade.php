@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-text">Dashboard</h1>
    <p class="text-text-secondary mt-1">Overview of your Super WMS website</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl p-5 border border-gray-100">
        <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="layers" class="w-4.5 h-4.5 text-forest"></i>
        </div>
        <p class="text-2xl font-bold text-text">{{ $stats['entries'] }} <span class="text-sm font-normal text-text-secondary">/ {{ $stats['total_entries'] }}</span></p>
        <p class="text-xs text-text-secondary mt-0.5">Published Entries</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100">
        <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="inbox" class="w-4.5 h-4.5 text-forest"></i>
        </div>
        <p class="text-2xl font-bold text-text">{{ $stats['submissions'] }}</p>
        <p class="text-xs text-text-secondary mt-0.5">New Submissions</p>
    </div>
    <div class="bg-white rounded-2xl p-5 border border-gray-100">
        <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="star" class="w-4.5 h-4.5 text-forest"></i>
        </div>
        <p class="text-2xl font-bold text-text">{{ $stats['reviews'] }}</p>
        <p class="text-xs text-text-secondary mt-0.5">Published Reviews</p>
    </div>
</div>

{{-- Charts row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Submissions trend (area chart) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-text mb-4">Submissions: Last 30 Days</h2>
        <div id="submissions-chart"></div>
    </div>

    {{-- Submissions by form (donut chart) --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-text mb-4">Submissions by Form</h2>
        <div id="forms-donut-chart"></div>
        @if(empty($submissionsByForm))
        <p class="text-xs text-text-secondary text-center mt-4">No submissions yet</p>
        @endif
    </div>
</div>

{{-- Recent Submissions table --}}
<div class="bg-white rounded-2xl border border-gray-100">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-text">Recent Submissions</h2>
        <a href="{{ route('admin.submissions.index') }}" class="text-xs text-forest hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto overscroll-x-contain">
        <table class="min-w-[640px] w-full">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-medium text-text-secondary uppercase tracking-wider px-6 py-3">Form</th>
                    <th class="text-left text-xs font-medium text-text-secondary uppercase tracking-wider px-6 py-3">Date</th>
                    <th class="text-left text-xs font-medium text-text-secondary uppercase tracking-wider px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse ($recentSubmissions as $submission)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-text">{{ $submission->form->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-4 text-sm text-text-secondary">{{ $submission->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $submission->status === 'new' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($submission->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-sm text-text-secondary">No submissions yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    function initCharts() {
        if (typeof window.ApexCharts === 'undefined') {
            setTimeout(initCharts, 50);
            return;
        }

        const chartDates = @json(array_keys($submissionsChart));
        const chartValues = @json(array_values($submissionsChart));

        if (chartDates.length > 0) {
            new window.ApexCharts(document.querySelector('#submissions-chart'), {
                chart: { type: 'area', height: 260, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [{ name: 'Submissions', data: chartValues }],
                xaxis: { categories: chartDates, labels: { style: { fontSize: '11px', colors: '#9ca3af' }, rotate: -45, rotateAlways: chartDates.length > 14 } },
                yaxis: { labels: { style: { fontSize: '11px', colors: '#9ca3af' } }, min: 0, forceNiceScale: true },
                colors: ['#27452B'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] } },
                stroke: { curve: 'smooth', width: 2 },
                dataLabels: { enabled: false },
                grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
                tooltip: { x: { format: 'MMM dd' } },
            }).render();
        } else {
            document.querySelector('#submissions-chart').innerHTML = '<p class="text-xs text-gray-400 text-center py-16">No data for the last 30 days</p>';
        }

        const formLabels = @json(array_keys($submissionsByForm));
        const formValues = @json(array_values($submissionsByForm));

        if (formLabels.length > 0) {
            new window.ApexCharts(document.querySelector('#forms-donut-chart'), {
                chart: { type: 'donut', height: 240, fontFamily: 'inherit' },
                series: formValues,
                labels: formLabels,
                colors: ['#27452B', '#3A6B41', '#5A9B63', '#8BC49A', '#BDE0C5', '#D4A574'],
                legend: { position: 'bottom', fontSize: '11px', markers: { size: 6 }, itemMargin: { horizontal: 8, vertical: 4 } },
                dataLabels: { enabled: false },
                plotOptions: { pie: { donut: { size: '60%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '12px', fontWeight: 600, color: '#374151' } } } } },
                stroke: { width: 2, colors: ['#fff'] },
            }).render();
        }
    }
    
    initCharts();
});
</script>
@endpush
@endsection
