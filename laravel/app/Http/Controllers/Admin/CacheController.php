<?php

namespace App\Http\Controllers\Admin;

use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        \App\Models\Setting::flushCache();

        // Log out the current admin
        Auth::logout();

        // Only clear the current user's session — do NOT truncate all sessions
        // to avoid forcibly logging out other active admins
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        // Return the cleanup page — handles client-side clearing then redirects to login
        return \Illuminate\Support\Facades\View::make('admin.cache-cleared');
    }
}
