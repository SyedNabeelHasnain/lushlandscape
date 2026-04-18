<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cache Cleared | Super WMS Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 1.25rem;
            padding: 2.5rem 3rem;
            text-align: center;
            max-width: 420px;
            width: 90%;
            box-shadow: 0 4px 24px rgba(0,0,0,.06);
        }
        .icon {
            width: 56px;
            height: 56px;
            background: #f0fdf4;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .icon svg { width: 28px; height: 28px; color: #16a34a; stroke: #16a34a; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        h1 { font-size: 1.125rem; font-weight: 700; color: #111827; margin-bottom: .5rem; }
        p { font-size: .875rem; color: #6b7280; line-height: 1.6; margin-bottom: 1.5rem; }
        .items {
            background: #f9fafb;
            border-radius: .75rem;
            padding: .875rem 1rem;
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .item {
            display: flex;
            align-items: center;
            gap: .625rem;
            font-size: .75rem;
            color: #374151;
            padding: .2rem 0;
        }
        .dot { width: 6px; height: 6px; background: #16a34a; border-radius: 50%; flex-shrink: 0; }
        .progress {
            height: 3px;
            background: #e5e7eb;
            border-radius: 9999px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        .bar {
            height: 100%;
            background: #16a34a;
            border-radius: 9999px;
            width: 0%;
            transition: width 2s linear;
        }
        .hint { font-size: .75rem; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><polyline points="9 12 11 14 15 10"/></svg>
        </div>
        <h1>All Caches Cleared</h1>
        <p>Server and client caches have been fully flushed. You will be redirected to login.</p>
        <div class="items">
            <div class="item"><div class="dot"></div>Application cache cleared</div>
            <div class="item"><div class="dot"></div>Config &amp; route cache cleared</div>
            <div class="item"><div class="dot"></div>View cache cleared</div>
            <div class="item"><div class="dot"></div>Sitemap removed (regenerates on next visit)</div>
            <div class="item"><div class="dot"></div>All server sessions wiped</div>
            <div class="item"><div class="dot"></div>Browser localStorage cleared</div>
            <div class="item"><div class="dot"></div>Browser sessionStorage cleared</div>
            <div class="item"><div class="dot"></div>Browser cookies cleared</div>
        </div>
        <div class="progress"><div class="bar" id="bar"></div></div>
        <p class="hint">Redirecting to login in 3 seconds…</p>
    </div>

    <script>
        // Client-side: clear all browser storage
        try { localStorage.clear(); } catch (e) {}
        try { sessionStorage.clear(); } catch (e) {}

        // Clear all cookies on this domain
        document.cookie.split(';').forEach(function (c) {
            var eqPos = c.indexOf('=');
            var name = eqPos > -1 ? c.slice(0, eqPos).trim() : c.trim();
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
            document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=' + location.hostname;
        });

        // Animate progress bar then redirect
        requestAnimationFrame(function () {
            document.getElementById('bar').style.width = '100%';
        });

        setTimeout(function () {
            window.location.href = '{{ route('login') }}?cleared=1';
        }, 3000);
    </script>
</body>
</html>
