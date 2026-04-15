@extends('admin.layouts.app')
@section('title', 'Search Analytics')
@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Search Analytics</h1>
            <p class="text-sm text-gray-500 mt-0.5">What visitors are searching for on your site</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @foreach([7, 30, 90] as $d)
            <a href="{{ route('admin.search-analytics') }}?days={{ $d }}"
               class="px-3 py-1.5 text-sm rounded-lg border transition {{ $period == $d ? 'bg-forest text-white border-forest' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $d }}d
            </a>
            @endforeach
        </div>
    </div>

    {{-- Stats strip --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Total Searches</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($totalSearches) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Unique Queries</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($uniqueQueries) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Zero-Result Rate</p>
            <p class="text-3xl font-bold {{ $zeroResultsPct > 30 ? 'text-red-600' : ($zeroResultsPct > 15 ? 'text-amber-600' : 'text-green-600') }}">
                {{ $zeroResultsPct }}%
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top Queries --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Top Search Queries</h2>
                <p class="text-xs text-gray-400 mt-0.5">Last {{ $period }} days</p>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($topQueries as $row)
                <div class="flex flex-col items-start gap-2 px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="text-xs font-mono text-gray-300 w-5 shrink-0">{{ $loop->iteration }}</span>
                        <div class="min-w-0">
                            <a href="{{ route('search.results', ['q' => $row->query]) }}" target="_blank"
                               class="text-sm font-medium text-gray-800 hover:text-forest transition truncate block">
                                {{ $row->query }}
                            </a>
                            <p class="text-xs text-gray-400">avg {{ round($row->avg_results) }} results</p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-forest shrink-0 ml-3">{{ number_format($row->count) }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-gray-400">No searches yet in this period.</div>
                @endforelse
            </div>
        </div>

        {{-- Zero-Results Queries --}}
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900">Zero-Result Queries</h2>
                <p class="text-xs text-gray-400 mt-0.5">Content gaps. Consider adding these</p>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($zeroResults as $row)
                <div class="flex flex-col items-start gap-2 px-5 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <span class="text-sm text-gray-700 break-words">{{ $row->query }}</span>
                    <span class="text-sm font-bold text-red-500 shrink-0 sm:ml-3">{{ $row->count }}×</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-sm text-gray-400">No zero-result searches. Great!</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Daily volume --}}
    @if($dailyVolume->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <h2 class="font-semibold text-gray-900 mb-4">Daily Search Volume</h2>
        @php $maxCount = $dailyVolume->max('count') ?: 1; @endphp
        <div class="flex h-24 min-w-[560px] items-end gap-1 overflow-x-auto pb-2">
            @foreach($dailyVolume as $day)
            @php $pct = round($day->count / $maxCount * 100); @endphp
            <div class="flex flex-col items-center gap-1 flex-1 min-w-[20px] group" title="{{ $day->date }}: {{ $day->count }} searches">
                <div class="w-full bg-forest/20 rounded-t group-hover:bg-forest/40 transition" style="height:{{ max($pct, 4) }}%"></div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-between text-xs text-gray-400 mt-1">
            <span>{{ $dailyVolume->first()?->date }}</span>
            <span>{{ $dailyVolume->last()?->date }}</span>
        </div>
    </div>
    @endif

    {{-- Recent Searches --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Recent Searches</h2>
        </div>
        <div class="overflow-x-auto overscroll-x-contain">
            <table class="min-w-[640px] w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-400 uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-5 py-3">Query</th>
                        <th class="text-left px-5 py-3">Results</th>
                        <th class="text-left px-5 py-3">Context</th>
                        <th class="text-left px-5 py-3">Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentSearches as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <a href="{{ route('search.results', ['q' => $log->query]) }}" target="_blank"
                               class="text-gray-800 hover:text-forest transition font-medium">{{ $log->query }}</a>
                        </td>
                        <td class="px-5 py-3">
                            <span class="{{ $log->results_count === 0 ? 'text-red-500 font-semibold' : 'text-gray-600' }}">
                                {{ $log->results_count }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400">{{ $log->page_context ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-400">{{ $log->created_at?->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
