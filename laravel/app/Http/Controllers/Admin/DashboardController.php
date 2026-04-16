<?php

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
            (SELECT "cities" as type, COUNT(*) as count FROM cities WHERE status = "published"
            UNION ALL SELECT "services", COUNT(*) FROM services WHERE status = "published"
            UNION ALL SELECT "active_pages", COUNT(*) FROM service_city_pages WHERE is_active = 1
            UNION ALL SELECT "total_pages", COUNT(*) FROM service_city_pages
            UNION ALL SELECT "submissions", COUNT(*) FROM form_submissions WHERE status = "new"
            UNION ALL SELECT "blog_posts", COUNT(*) FROM blog_posts WHERE status = "published"
            UNION ALL SELECT "reviews", COUNT(*) FROM reviews WHERE status = "published"
            UNION ALL SELECT "portfolio", COUNT(*) FROM portfolio_projects WHERE status = "published") as counts
        '))->pluck('count', 'type')->all();

        $stats = [
            'cities' => $counts['cities'] ?? 0,
            'services' => $counts['services'] ?? 0,
            'active_pages' => $counts['active_pages'] ?? 0,
            'total_pages' => $counts['total_pages'] ?? 0,
            'submissions' => $counts['submissions'] ?? 0,
            'blog_posts' => $counts['blog_posts'] ?? 0,
            'reviews' => $counts['reviews'] ?? 0,
            'portfolio' => $counts['portfolio'] ?? 0,
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
