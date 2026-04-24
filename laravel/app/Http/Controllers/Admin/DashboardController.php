<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = DB::table(DB::raw('
            (SELECT "entries" as type, COUNT(*) as count FROM entries WHERE status = "published"
            UNION ALL SELECT "total_entries", COUNT(*) FROM entries
            UNION ALL SELECT "submissions", COUNT(*) FROM form_submissions WHERE status = "new"
            UNION ALL SELECT "reviews", COUNT(*) FROM reviews WHERE status = "published") as counts
        '))->pluck('count', 'type')->all();

        $stats = [
            'entries' => $counts['entries'] ?? 0,
            'total_entries' => $counts['total_entries'] ?? 0,
            'submissions' => $counts['submissions'] ?? 0,
            'reviews' => $counts['reviews'] ?? 0,
        ];

        $recentSubmissions = FormSubmission::with('form')
            ->latest()
            ->take(10)
            ->get();

        // Submissions per day (last 30 days)
        $submissionsChart = FormSubmission::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->all();

        // Submissions by form (for donut chart)
        $submissionsByForm = FormSubmission::select('form_id', DB::raw('COUNT(*) as total'))
            ->with('form:id,name')
            ->groupBy('form_id')
            ->get()
            ->mapWithKeys(fn ($s) => [$s->form->name ?? 'Unknown' => $s->total])
            ->all();

        return View::make('admin.pages.dashboard', compact(
            'stats', 'recentSubmissions', 'submissionsChart', 'submissionsByForm'
        ));
    }
}
