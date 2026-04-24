<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemapPath = public_path('sitemap.xml');
        if (! file_exists($sitemapPath)) {
            // Return empty sitemap if not yet generated — avoid blocking HTTP request
            // Sitemap should be generated via cron: sitemap:generate (daily)
            return response('<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
</urlset>', 200)->header('Content-Type', 'application/xml');
        }

        return response()->file($sitemapPath, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
