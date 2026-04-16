<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SearchAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = min((int) $request->query('days', 30), 365);
        $since = now()->subDays($period);

        // Top queries by frequency
        $topQueries = SearchLog::select('query', DB::raw('COUNT(*) as count'), DB::raw('AVG(results_count) as avg_results'))
            ->where('created_at', '>=', $since)
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(50)
            ->get();

        // Zero-results queries (search gaps)
        $zeroResults = SearchLog::select('query', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $since)
            ->where('results_count', 0)
            ->groupBy('query')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        // Daily search volume (last 30 days)
        $dailyVolume = SearchLog::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Total stats
        $totalSearches = SearchLog::where('created_at', '>=', $since)->count();
        $uniqueQueries = SearchLog::where('created_at', '>=', $since)->distinct('query')->count('query');
        $zeroResultsPct = $totalSearches > 0
            ? round(SearchLog::where('created_at', '>=', $since)->where('results_count', 0)->count() / $totalSearches * 100, 1)
            : 0;

        // Recent searches
        $recentSearches = SearchLog::orderByDesc('created_at')->limit(30)->get();

        return View::make('admin.search-analytics.index', compact(
            'topQueries', 'zeroResults', 'dailyVolume',
            'totalSearches', 'uniqueQueries', 'zeroResultsPct',
            'recentSearches', 'period'
        ));
    }
}
