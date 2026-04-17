<?php

namespace App\Console\Commands;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\CardTemplate;
use App\Models\City;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\MediaAsset;
use App\Models\Popup;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\Redirect;
use App\Models\Review;
use App\Models\ReviewCategory;
use App\Models\SecurityRule;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Models\StaticPage;
use App\Models\ThemeLayout;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RuntimeSmokeAudit extends Command
{
    protected $signature = 'app:smoke-audit
                            {--limit=0 : Limit per dynamic content group (0 = all)}
                            {--admin : Include authenticated admin route checks}';

    protected $description = 'Run a runtime smoke audit across public pages and optional admin screens using the current configured database';

    public function handle(): int
    {
        $limit = max(0, (int) $this->option('limit'));
        $checks = $this->publicChecks($limit);

        if ($this->option('admin')) {
            $checks = $checks->merge($this->adminChecks($limit));
        }

        if ($checks->isEmpty()) {
            $this->warn('No runtime smoke checks were generated.');

            return self::FAILURE;
        }

        $this->info('Running '.$checks->count().' runtime smoke checks...');

        $results = collect();

        foreach ($checks as $check) {
            $results->push($this->runCheck($check));
        }

        $passed = $results->where('ok', true);
        $failed = $results->where('ok', false);

        $this->newLine();
        $this->info('Smoke audit summary:');
        $this->line(' - Total checks: '.$results->count());
        $this->line(' - Passed: '.$passed->count());
        $this->line(' - Failed: '.$failed->count());

        $byGroup = $results->groupBy('group')->map(fn (Collection $group) => [
            'passed' => $group->where('ok', true)->count(),
            'failed' => $group->where('ok', false)->count(),
            'total' => $group->count(),
        ]);

        foreach ($byGroup as $group => $stats) {
            $label = str_pad($group, 18);
            $this->line(" - {$label} {$stats['passed']}/{$stats['total']} passed");
        }

        if ($failed->isNotEmpty()) {
            $this->newLine();
            $this->error('Failures:');

            foreach ($failed as $failure) {
                $this->line(' - ['.$failure['group'].'] '.$failure['uri'].' => '.$failure['summary']);
            }

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Runtime smoke audit passed cleanly.');

        return self::SUCCESS;
    }

    private function publicChecks(int $limit): Collection
    {
        $checks = collect([
            $this->check('public', '/'),
            $this->check('public', '/services'),
            $this->check('public', '/locations'),
            $this->check('public', '/blog'),
            $this->check('public', '/portfolio'),
            $this->check('public', '/contact'),
            $this->check('public', '/consultation'),
            $this->check('public', '/faqs'),
            $this->check('public', '/llms.txt'),
            $this->check('public', '/llms-full.txt'),
            $this->check('public', '/sitemap.xml'),
        ]);

        $searchTerm = $this->searchTerm();
        $checks = $checks->merge([
            $this->check('search', '/search?q='.urlencode($searchTerm)),
            $this->check('search', '/search?q='.urlencode($searchTerm).'&type=services'),
            $this->check('search', '/search?q='.urlencode($searchTerm).'&type=categories'),
            $this->check('search', '/search?q='.urlencode($searchTerm).'&type=cities'),
            $this->check('search', '/search/live?q='.urlencode($searchTerm)),
        ]);

        $checks = $checks
            ->merge($this->serviceCategoryChecks($limit))
            ->merge($this->serviceChecks($limit))
            ->merge($this->cityChecks($limit))
            ->merge($this->serviceCityChecks($limit))
            ->merge($this->staticPageChecks($limit))
            ->merge($this->blogCategoryChecks($limit))
            ->merge($this->blogChecks($limit))
            ->merge($this->portfolioCategoryChecks($limit))
            ->merge($this->portfolioChecks($limit));

        return $checks->unique('uri')->values();
    }

    private function adminChecks(int $limit): Collection
    {
        $admin = User::query()->where('role', 'admin')->orderBy('id')->first();
        if (! $admin) {
            $this->warn('No admin user found. Skipping admin smoke checks.');

            return collect();
        }

        $checks = collect([
            $this->check('admin', '/admin', true),
            $this->check('admin', '/admin/settings', true),
            $this->check('admin', '/admin/home-page', true),
            $this->check('admin', '/admin/search-analytics', true),
            $this->check('admin', '/admin/import-export', true),
            $this->check('admin', '/admin/bulk-import', true),
            $this->check('admin', '/admin/bulk-import/export-library', true),
            $this->check('admin', '/admin/bulk-import/generate-dataset', true),
            $this->check('admin', '/admin/service-city-matrix', true),
            $this->check('admin', '/admin/service-categories', true),
            $this->check('admin', '/admin/services', true),
            $this->check('admin', '/admin/cities', true),
            $this->check('admin', '/admin/service-city-pages', true),
            $this->check('admin', '/admin/static-pages', true),
            $this->check('admin', '/admin/media', true),
            $this->check('admin', '/admin/media/json', true),
            $this->check('admin', '/admin/submissions', true),
            $this->check('admin', '/admin/blog-posts', true),
            $this->check('admin', '/admin/blog-categories', true),
            $this->check('admin', '/admin/faqs', true),
            $this->check('admin', '/admin/faq-categories', true),
            $this->check('admin', '/admin/reviews', true),
            $this->check('admin', '/admin/review-categories', true),
            $this->check('admin', '/admin/portfolio', true),
            $this->check('admin', '/admin/portfolio-categories', true),
            $this->check('admin', '/admin/forms', true),
            $this->check('admin', '/admin/popups', true),
            $this->check('admin', '/admin/redirects', true),
            $this->check('admin', '/admin/security-rules', true),
            $this->check('admin', '/admin/theme-layouts', true),
            $this->check('admin', '/admin/card-templates', true),
            $this->check('admin', '/admin/services/create', true),
            $this->check('admin', '/admin/service-categories/create', true),
            $this->check('admin', '/admin/cities/create', true),
            $this->check('admin', '/admin/service-city-pages/create', true),
            $this->check('admin', '/admin/static-pages/create', true),
            $this->check('admin', '/admin/blog-posts/create', true),
            $this->check('admin', '/admin/blog-categories/create', true),
            $this->check('admin', '/admin/faqs/create', true),
            $this->check('admin', '/admin/faq-categories/create', true),
            $this->check('admin', '/admin/reviews/create', true),
            $this->check('admin', '/admin/review-categories/create', true),
            $this->check('admin', '/admin/portfolio/create', true),
            $this->check('admin', '/admin/portfolio-categories/create', true),
            $this->check('admin', '/admin/forms/create', true),
            $this->check('admin', '/admin/popups/create', true),
            $this->check('admin', '/admin/redirects/create', true),
            $this->check('admin', '/admin/security-rules/create', true),
            $this->check('admin', '/admin/theme-layouts/create', true),
            $this->check('admin', '/admin/card-templates/create', true),
            $this->check('admin', '/admin/media/create', true),
            $this->check('admin', '/admin/blocks/home/0', true),
            $this->check('admin', '/admin/content-blocks/home/0/export', true),
        ]);

        $checks = $checks
            ->merge($this->adminEditChecks(ServiceCategory::query()->where('status', 'published'), '/admin/service-categories/%d/edit', $limit))
            ->merge($this->adminEditChecks(Service::query()->where('status', 'published'), '/admin/services/%d/edit', $limit))
            ->merge($this->adminEditChecks(City::query()->where('status', 'published'), '/admin/cities/%d/edit', $limit))
            ->merge($this->adminEditChecks(ServiceCityPage::query()->where('is_active', true), '/admin/service-city-pages/%d/edit', $limit))
            ->merge($this->adminEditChecks(StaticPage::query()->where('status', 'published'), '/admin/static-pages/%d/edit', $limit))
            ->merge($this->adminEditChecks(BlogCategory::query()->where('status', 'published'), '/admin/blog-categories/%d/edit', $limit))
            ->merge($this->adminEditChecks(BlogPost::query()->where('status', 'published'), '/admin/blog-posts/%d/edit', $limit))
            ->merge($this->adminEditChecks(FaqCategory::query()->where('status', 'published'), '/admin/faq-categories/%d/edit', $limit))
            ->merge($this->adminEditChecks(Faq::query()->where('status', 'published'), '/admin/faqs/%d/edit', $limit))
            ->merge($this->adminEditChecks(ReviewCategory::query()->where('status', 'published'), '/admin/review-categories/%d/edit', $limit))
            ->merge($this->adminEditChecks(Review::query()->where('status', 'published'), '/admin/reviews/%d/edit', $limit))
            ->merge($this->adminEditChecks(PortfolioCategory::query()->where('status', 'published'), '/admin/portfolio-categories/%d/edit', $limit))
            ->merge($this->adminEditChecks(PortfolioProject::query()->where('status', 'published'), '/admin/portfolio/%d/edit', $limit))
            ->merge($this->adminEditChecks(Form::query()->orderBy('id'), '/admin/forms/%d/edit', $limit))
            ->merge($this->adminEditChecks(Popup::query()->orderBy('id'), '/admin/popups/%d/edit', $limit))
            ->merge($this->adminEditChecks(Redirect::query()->orderBy('id'), '/admin/redirects/%d/edit', $limit))
            ->merge($this->adminEditChecks(SecurityRule::query()->orderBy('id'), '/admin/security-rules/%d/edit', $limit))
            ->merge($this->adminEditChecks(ThemeLayout::query()->orderBy('id'), '/admin/theme-layouts/%d/edit', $limit))
            ->merge($this->adminEditChecks(CardTemplate::query()->orderBy('id'), '/admin/card-templates/%d/edit', $limit))
            ->merge($this->adminEditChecks(MediaAsset::query()->orderBy('id'), '/admin/media/%d/edit', $limit))
            ->merge($this->adminEditChecks(FormSubmission::query()->orderByDesc('id'), '/admin/submissions/%d', $limit))
            ->merge($this->adminBlockChecks(ServiceCategory::query()->where('status', 'published'), 'service_category', $limit))
            ->merge($this->adminBlockChecks(Service::query()->where('status', 'published'), 'service', $limit))
            ->merge($this->adminBlockChecks(City::query()->where('status', 'published'), 'city', $limit))
            ->merge($this->adminBlockChecks(ServiceCityPage::query()->where('is_active', true), 'service_city_page', $limit))
            ->merge($this->adminBlockChecks(StaticPage::query()->where('status', 'published'), 'static_page', $limit))
            ->merge($this->adminBlockChecks(BlogPost::query()->where('status', 'published'), 'blog_post', $limit))
            ->merge($this->adminBlockChecks(PortfolioProject::query()->where('status', 'published'), 'portfolio_project', $limit))
            ->merge($this->adminBlockChecks(ThemeLayout::query()->orderBy('id'), 'theme_layout', $limit))
            ->merge($this->adminBlockChecks(CardTemplate::query()->orderBy('id'), 'template_card', $limit))
            ->merge($this->adminExportCheck(ServiceCategory::query()->where('status', 'published'), 'service_category'))
            ->merge($this->adminExportCheck(Service::query()->where('status', 'published'), 'service'))
            ->merge($this->adminExportCheck(City::query()->where('status', 'published'), 'city'))
            ->merge($this->adminExportCheck(ServiceCityPage::query()->where('is_active', true), 'service_city_page'))
            ->merge($this->adminExportCheck(StaticPage::query()->where('status', 'published'), 'static_page'))
            ->merge($this->adminExportCheck(BlogPost::query()->where('status', 'published'), 'blog_post'))
            ->merge($this->adminExportCheck(PortfolioProject::query()->where('status', 'published'), 'portfolio_project'))
            ->merge($this->adminExportCheck(ThemeLayout::query()->orderBy('id'), 'theme_layout'))
            ->merge($this->adminExportCheck(CardTemplate::query()->orderBy('id'), 'template_card'));

        return $checks
            ->map(fn (array $check) => $check + ['admin_user' => $admin])
            ->unique('uri')
            ->values();
    }

    private function serviceCategoryChecks(int $limit): Collection
    {
        $query = ServiceCategory::query()
            ->where('status', 'published')
            ->orderBy('sort_order');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()->map(fn (ServiceCategory $category) => $this->check('service-category', '/services/'.$category->slug_final));
    }

    private function serviceChecks(int $limit): Collection
    {
        $query = Service::query()
            ->where('status', 'published')
            ->with('category:id,slug_final')
            ->orderBy('sort_order');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->filter(fn (Service $service) => filled($service->frontend_url))
            ->map(fn (Service $service) => $this->check('service', $this->toRelativeUri($service->frontend_url)));
    }

    private function cityChecks(int $limit): Collection
    {
        $query = City::query()
            ->where('status', 'published')
            ->orderBy('sort_order');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->filter(fn (City $city) => filled($city->frontend_url))
            ->map(fn (City $city) => $this->check('city', $this->toRelativeUri($city->frontend_url)));
    }

    private function serviceCityChecks(int $limit): Collection
    {
        $query = ServiceCityPage::query()
            ->where('is_active', true)
            ->orderBy('sort_order');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->filter(fn (ServiceCityPage $page) => filled($page->frontend_url))
            ->map(fn (ServiceCityPage $page) => $this->check('service-city', $this->toRelativeUri($page->frontend_url)));
    }

    private function staticPageChecks(int $limit): Collection
    {
        $query = StaticPage::query()
            ->where('status', 'published')
            ->orderBy('slug');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->filter(fn (StaticPage $page) => filled($page->frontend_url))
            ->map(fn (StaticPage $page) => $this->check('static', $this->toRelativeUri($page->frontend_url)));
    }

    private function blogCategoryChecks(int $limit): Collection
    {
        $query = BlogCategory::query()
            ->where('status', 'published')
            ->orderBy('sort_order');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()->map(fn (BlogCategory $category) => $this->check('blog-category', route('blog.category', ['slug' => $category->slug], false)));
    }

    private function blogChecks(int $limit): Collection
    {
        $query = BlogPost::query()
            ->where('status', 'published')
            ->orderByDesc('published_at');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->filter(fn (BlogPost $post) => filled($post->frontend_url))
            ->map(fn (BlogPost $post) => $this->check('blog', $this->toRelativeUri($post->frontend_url)));
    }

    private function portfolioCategoryChecks(int $limit): Collection
    {
        $query = PortfolioCategory::query()
            ->where('status', 'published')
            ->orderBy('sort_order');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()->map(fn (PortfolioCategory $category) => $this->check('portfolio-category', route('portfolio.category', ['slug' => $category->slug], false)));
    }

    private function portfolioChecks(int $limit): Collection
    {
        $query = PortfolioProject::query()
            ->where('status', 'published')
            ->orderByDesc('id');

        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get()
            ->filter(fn (PortfolioProject $project) => filled($project->frontend_url))
            ->map(fn (PortfolioProject $project) => $this->check('portfolio', $this->toRelativeUri($project->frontend_url)));
    }

    private function adminEditChecks($query, string $pattern, int $limit): Collection
    {
        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get(['id'])->map(fn ($model) => $this->check('admin', sprintf($pattern, $model->id), true));
    }

    private function adminBlockChecks($query, string $pageType, int $limit): Collection
    {
        if ($limit > 0) {
            $query->limit($limit);
        }

        return $query->get(['id'])->map(fn ($model) => $this->check('admin', sprintf('/admin/blocks/%s/%d', $pageType, $model->id), true));
    }

    private function adminExportCheck($query, string $type): Collection
    {
        $model = $query->first(['id']);

        if (! $model) {
            return collect();
        }

        return collect([
            $this->check('admin', sprintf('/admin/content-blocks/%s/%d/export', $type, $model->id), true),
        ]);
    }

    private function searchTerm(): string
    {
        $serviceName = Service::query()->where('status', 'published')->value('name');
        if (is_string($serviceName) && $serviceName !== '') {
            $term = collect(preg_split('/\s+/', $serviceName) ?: [])
                ->map(fn (string $part) => trim($part))
                ->first(fn (string $part) => mb_strlen($part) >= 3);

            if ($term) {
                return $term;
            }
        }

        return 'landscape';
    }

    private function runCheck(array $check): array
    {
        $result = $this->dispatch($check['uri'], $check['authenticated'] ? ($check['admin_user'] ?? null) : null);

        $prefix = $result['ok'] ? '<info>PASS</info>' : '<error>FAIL</error>';
        $this->line(sprintf(
            '%s [%s] %s%s',
            $prefix,
            $check['group'],
            $check['uri'],
            $result['summary'] ? ' -> '.$result['summary'] : ''
        ));

        return [
            'group' => $check['group'],
            'uri' => $check['uri'],
            'ok' => $result['ok'],
            'status' => $result['status'],
            'summary' => $result['summary'],
        ];
    }

    private function dispatch(string $uri, ?User $adminUser = null, int $depth = 0): array
    {
        if ($depth > 5) {
            return [
                'ok' => false,
                'status' => 0,
                'summary' => 'redirect loop exceeded',
            ];
        }

        $kernel = app(HttpKernel::class);
        $request = Request::create($uri, 'GET', [], [], [], $this->serverVars());
        $request->headers->set('Accept', Str::contains($uri, '/search/live') ? 'application/json' : 'text/html,application/xhtml+xml');
        $request->headers->set('User-Agent', 'LushRuntimeSmokeAudit/1.0');

        if ($adminUser) {
            Auth::shouldUse('web');
            Auth::guard('web')->setUser($adminUser);
            $request->setUserResolver(fn () => $adminUser);
        }

        try {
            $response = $kernel->handle($request);
            $status = $response->getStatusCode();
            $summary = (string) $status;

            if ($response instanceof RedirectResponse) {
                $location = $response->headers->get('Location') ?: '';
                $nextUri = $this->toRelativeUri($location);

                if ($nextUri === $uri || $nextUri === '') {
                    return [
                        'ok' => false,
                        'status' => $status,
                        'summary' => "{$status} redirect without a followable internal target",
                    ];
                }

                $followed = $this->dispatch($nextUri, $adminUser, $depth + 1);
                $followed['summary'] = "{$status} -> {$followed['summary']}";

                return $followed;
            }

            if ($status >= 200 && $status < 300) {
                $contentType = (string) $response->headers->get('Content-Type', '');
                $body = '';
                if (Str::contains($contentType, ['text/html', 'application/xhtml+xml'])) {
                    $body = (string) $response->getContent();
                    $haystack = strtolower($body);

                    $forbidden = $this->lookupAssertion($uri, 'forbidden');
                    foreach ($forbidden as $needle) {
                        if ($needle !== '' && str_contains($haystack, strtolower($needle))) {
                            return [
                                'ok' => false,
                                'status' => $status,
                                'summary' => "contains forbidden phrase: {$needle}",
                            ];
                        }
                    }

                    $required = $this->lookupAssertion($uri, 'required');
                    foreach ($required as $needle) {
                        if ($needle !== '' && ! str_contains($body, $needle)) {
                            return [
                                'ok' => false,
                                'status' => $status,
                                'summary' => "missing required phrase: {$needle}",
                            ];
                        }
                    }
                }

                return [
                    'ok' => true,
                    'status' => $status,
                    'summary' => $summary,
                ];
            }

            return [
                'ok' => false,
                'status' => $status,
                'summary' => $summary,
            ];
        } catch (\Throwable $exception) {
            return [
                'ok' => false,
                'status' => 0,
                'summary' => class_basename($exception).': '.$exception->getMessage(),
            ];
        }
    }

    private function check(string $group, string $uri, bool $authenticated = false): array
    {
        return [
            'group' => $group,
            'uri' => $uri,
            'authenticated' => $authenticated,
        ];
    }

    private function lookupAssertion(string $uri, string $key): array
    {
        $assertions = $this->assertions();

        return $assertions[$uri][$key] ?? [];
    }

    private function assertions(): array
    {
        $premiumForbidden = [
            'free estimate',
            'free estimates',
            'free quote',
            'free quotes',
            'get a quote',
            'request a quote',
            'quote request',
            'no obligation',
            'no-obligation',
            'seasonal deals',
            'transparent pricing',
        ];

        return [
            '/' => [
                'forbidden' => $premiumForbidden,
                'required' => [],
            ],
            '/services' => [
                'forbidden' => $premiumForbidden,
                'required' => [],
            ],
            '/locations' => [
                'forbidden' => $premiumForbidden,
                'required' => [],
            ],
            '/blog' => [
                'forbidden' => $premiumForbidden,
                'required' => [],
            ],
            '/portfolio' => [
                'forbidden' => $premiumForbidden,
                'required' => [],
            ],
            '/contact' => [
                'forbidden' => $premiumForbidden,
                'required' => [],
            ],
            '/consultation' => [
                'forbidden' => $premiumForbidden,
                'required' => ['Project Consultation', 'Request a Consultation'],
            ],
        ];
    }

    private function toRelativeUri(string $uri): string
    {
        if ($uri === '') {
            return '';
        }

        if (Str::startsWith($uri, ['http://', 'https://'])) {
            $path = parse_url($uri, PHP_URL_PATH) ?: '/';
            $query = parse_url($uri, PHP_URL_QUERY);

            return $query ? $path.'?'.$query : $path;
        }

        return Str::startsWith($uri, '/') ? $uri : '/'.$uri;
    }

    private function serverVars(): array
    {
        $appUrl = config('app.url');
        $host = parse_url($appUrl, PHP_URL_HOST) ?: 'localhost';
        $scheme = parse_url($appUrl, PHP_URL_SCHEME) ?: 'https';
        $port = (int) (parse_url($appUrl, PHP_URL_PORT) ?: ($scheme === 'https' ? 443 : 80));

        return [
            'HTTP_HOST' => $host,
            'HTTPS' => $scheme === 'https' ? 'on' : 'off',
            'REQUEST_SCHEME' => $scheme,
            'SERVER_PORT' => $port,
            'REMOTE_ADDR' => '127.0.0.1',
            'SERVER_NAME' => $host,
        ];
    }
}
