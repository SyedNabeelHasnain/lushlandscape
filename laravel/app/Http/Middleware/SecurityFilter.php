<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\SecurityRule;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SecurityFilter
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();

        $blockedIps = Cache::remember('security_blocked_ips', 300, fn () => SecurityRule::where('type', 'ip')
            ->where('action', 'block')
            ->where('is_active', true)
            ->pluck('value')
            ->flip()
            ->all()
        );

        if (isset($blockedIps[$ip])) {
            abort(403);
        }

        return $next($request);
    }
}
