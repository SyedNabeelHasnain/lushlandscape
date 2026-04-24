<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class CacheController extends Controller
{
    public function clear(Request $request)
    {
        // Server: clear all Laravel framework caches
        Artisan::call('optimize:clear');

        // Delete sitemap so it regenerates fresh on next request
        $sitemap = public_path('sitemap.xml');
        if (file_exists($sitemap) && ! unlink($sitemap)) {
            Log::warning('CacheController: failed to delete sitemap.xml', ['path' => $sitemap]);
        }

        // Clear application cache (settings, redirects, security rules, etc.)
        Cache::forget('redirects_map');
        Cache::forget('security_blocked_ips');
        Setting::flushCache();

        // Log out the current admin
        Auth::logout();

        // Only clear the current user's session — do NOT truncate all sessions
        // to avoid forcibly logging out other active admins
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Return the cleanup page — handles client-side clearing then redirects to login
        return View::make('admin.cache-cleared');
    }
}
