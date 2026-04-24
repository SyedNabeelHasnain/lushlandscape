<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip redirect lookups for admin routes
        if (str_starts_with($request->path(), 'admin')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->path(), '/');

        if ($path === '/') {
            return $next($request);
        }

        $redirects = Cache::remember('redirects_map', 3600, fn () => Redirect::where('is_active', true)
            ->get(['id', 'old_url', 'new_url', 'status_code'])
            ->keyBy('old_url')
        );

        $redirect = $redirects->get($path);

        if ($redirect) {
            Redirect::where('id', $redirect->id)->increment('hit_count');

            return redirect($redirect->new_url, $redirect->status_code);
        }

        return $next($request);
    }
}
