<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(self)');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // CSP — permissive enough for CMS with Google Fonts/Maps, inline Alpine directives, and Vite assets
        $directives = [
            'default-src' => "'self'",
            'script-src' => "'self' 'unsafe-eval' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com",
            'style-src' => "'self' 'unsafe-inline' https://fonts.googleapis.com",
            'img-src' => "'self' data: blob: https:",
            'font-src' => "'self' https://fonts.gstatic.com",
            'connect-src' => "'self' https://www.google-analytics.com https://www.googletagmanager.com",
            'frame-src' => "'self' https://www.google.com https://maps.google.com https://www.youtube.com https://player.vimeo.com",
            'object-src' => "'none'",
            'base-uri' => "'self'",
            'form-action' => "'self'",
            'frame-ancestors' => "'self'",
        ];

        // Relax CSP for Vite HMR in development
        $isLocalDevHost = in_array($request->getHost(), ['localhost', '127.0.0.1'], true)
            || str_ends_with($request->getHost(), '.test');

        if (config('app.debug') && $isLocalDevHost) {
            $directives['connect-src'] .= ' ws://localhost:* http://localhost:* ws://*.test:* http://*.test:*';
            $directives['script-src'] .= ' http://localhost:* http://*.test:*';
            $directives['style-src'] .= ' http://localhost:* http://*.test:*';
            $directives['img-src'] .= ' http://localhost:* http://*.test:*';
            $directives['font-src'] .= ' http://localhost:* http://*.test:*';
        }

        $csp = collect($directives)->map(fn ($val, $key) => "$key $val")->implode('; ');
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
