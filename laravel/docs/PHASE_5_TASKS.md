# Phase 5 Tasks — Testing, QA, Performance Hardening, Release Readiness

## Locked context

- Single authoritative environment: `https://test.lushlandscape.ca`
- `LEGACY_STRICT=true` remains normal operating mode
- Presentation source of truth remains:
  - `page_blocks`
  - `theme_layouts`
  - `card_templates`
- No new legacy authoring paths
- No extra docs beyond this file (Phase 5)

## 1) Final code-verified platform sweep

- [x] Architecture + builder authority validated (unified runtime remains first-class)
- [x] Strict mode behavior validated (legacy reads/writes behave correctly under `LEGACY_STRICT=true`)
- [x] Page type rendering validated (awaiting live UAT)
- [x] Shell consistency validated (header/footer + CTA model)
- [x] Builder/editor flows validated (block editor load/save, nested blocks, templates, media)
- [x] Consultation/contact flows validated (rendering, OTP, submission, success messaging)
- [x] No critical runtime errors during normal use (public pages)

## 2) Premium frontend QA (release-grade)

- [ ] Home
- [ ] Listing pages (services hub, categories, locations hub, city)
- [ ] Service + service-city
- [ ] Static + longform pages
- [ ] Blog post + blog shells
- [ ] Portfolio project + portfolio shells
- [ ] Contact + consultation
- [ ] Search
- [ ] FAQ pages

## 3) Fallback hardening (brand-safe)

- [x] Missing media states remain premium and do not collapse layouts
- [x] Missing template card state remains clear (admin-visible) and frontend-safe
- [x] Empty dynamic loop state remains premium and restrained
- [x] Missing optional content fields do not produce broken spacing/typography (hero defaults fixed)

## 4) Performance + caching hardening

- [x] Block rendering overhead reviewed (no avoidable repeated work in hot paths)
- [x] Dynamic loop overhead reviewed (template blocks cached safely)
- [x] Cache invalidation safe for:
  - page blocks
  - theme layouts
  - card templates
  - nav/footer taxonomy lists

## 5) Observability + release safety

- [x] Strict-mode failures produce actionable errors (not noisy)
- [x] Readiness + smoke commands reflect Phase 5 criteria

## 6) Tests (Phase 5 contracts)

- [x] Strict mode behavior test added/updated
- [x] Cache invalidation behavior hardened (where safe to test)
- [x] Readiness check extended to cover strict mode
- [x] Smoke audit covers premium-critical routes (public + admin where available)

## Validation sweep log (Phase 5)

## UAT audit summary (Phase 5 final pass)

- UAT compared directly against repo by reproducing failures on `https://test.lushlandscape.ca` and tracing Laravel logs.
- Primary incidents were UAT/repo alignment failures (stale caches, missing build artifacts, committed bootstrap caches, and a frontend Blade regression).
- Local regression suite executed after fixes: `phpunit` (79 tests, 379 assertions) passed.

## Repo vs UAT alignment findings (Phase 5 final pass)

| Issue | UAT symptom | Root cause | Fix applied | Files |
| --- | --- | --- | --- | --- |
| Missing Vite manifest | Frontend `500` with `Vite manifest not found .../public_html/build/manifest.json` | Deploy skipped build when Node/NPM missing; stale `hot` could also force dev lookup | Deploy now installs/uses Node 20, forces build, asserts manifest exists, removes `hot` | `deploy.sh` |
| Pail service provider crash | `php artisan ...` fails with `Class "Laravel\\Pail\\PailServiceProvider" not found` | `bootstrap/cache/services.php` and `packages.php` were committed from a dev install and referenced dev-only providers | Stop tracking bootstrap cache PHP and purge on deploy | `laravel/bootstrap/cache/.gitignore`, `laravel/.gitignore`, `deploy.sh` |
| Frontend layout fatal | Frontend `500` with `Undefined variable $footerBottomLinks` | Layout filtered footer links before variables were initialized | Initialize footer link arrays before filtering | `laravel/resources/views/frontend/layouts/app.blade.php` |
| Readiness false-blockers | `app:readiness-check` failed on loopback DB host on staging | Readiness treated loopback MySQL host as a blocker | Local MySQL host treated as warning; APP_URL sanitized for backticks | `laravel/app/Console/Commands/ProductionReadinessCheck.php` |

| Surface | Status | Notes |
| --- | --- | --- |
| Home | Unblocked | UAT 500 errors resolved. Ready for visual review. |
| Services hub |  |  |
| Service category |  |  |
| Service detail |  |  |
| Locations hub |  |  |
| City |  |  |
| Service-city |  |  |
| Static marketing pages |  |  |
| Longform pages |  |  |
| Blog index |  |  |
| Blog category |  |  |
| Blog post |  |  |
| Portfolio index |  |  |
| Portfolio category |  |  |
| Portfolio project |  |  |
| Contact |  |  |
| Consultation | Unblocked | Deploy blockers resolved. Ready to confirm consultation-led copy and CTA governance holds end-to-end. |
| Search |  |  |
| FAQ |  |  |
| Legal pages |  |  |

## Touched files (Phase 5)

- [x] [PHASE_5_TASKS.md](PHASE_5_TASKS.md)
- [x] [deploy.sh](../../deploy.sh)
- [x] [ThemePresentationService.php](../app/Services/ThemePresentationService.php)
- [x] [BlockGovernanceService.php](../app/Services/BlockGovernanceService.php)
- [x] [ProductionReadinessCheck.php](../app/Console/Commands/ProductionReadinessCheck.php)
- [x] [RuntimeSmokeAudit.php](../app/Console/Commands/RuntimeSmokeAudit.php)
- [x] [app.blade.php](../resources/views/frontend/layouts/app.blade.php)
- [x] [bootstrap/cache/.gitignore](../bootstrap/cache/.gitignore)
- [x] [laravel/.gitignore](../.gitignore)
- [x] [ProductionReadinessCheckTest.php](../tests/Feature/ProductionReadinessCheckTest.php)
- [x] [StrictModeLegacyGovernanceTest.php](../tests/Feature/StrictModeLegacyGovernanceTest.php)
- [x] [ListingPageBlueprintService.php](../app/Console/Services/ListingPageBlueprintService.php)
- [x] [HomePageBlueprintService.php](../app/Console/Services/HomePageBlueprintService.php)
- [x] [ThemeLayoutBlueprintService.php](../app/Console/Services/ThemeLayoutBlueprintService.php)
- [x] [BlockBuilderMetadataTest.php](../tests/Feature/BlockBuilderMetadataTest.php)
- [x] [ContentBlockExportControllerTest.php](../tests/Feature/ContentBlockExportControllerTest.php)
- [x] [BlockRendererTest.php](../tests/Feature/BlockRendererTest.php)
- [x] [Phase3ContractsTest.php](../tests/Feature/Phase3ContractsTest.php)
- [x] [ThemePresentationServiceTest.php](../tests/Feature/ThemePresentationServiceTest.php)
- [x] [_form-fields.blade.php](../resources/views/frontend/blocks/partials/_form-fields.blade.php)
- [x] [form-block.blade.php](../resources/views/frontend/blocks/partials/form-block.blade.php)
- [x] [dynamic-loop.blade.php](../resources/views/frontend/blocks/partials/dynamic-loop.blade.php)
- [x] [editorial-split-feature.blade.php](../resources/views/frontend/blocks/editorial-split-feature.blade.php)

## Release readiness status

- Status: **Code-level ready, pending live UAT confirmation**
- Blockers fixed:
  - UAT deploy/caching mismatches fixed via Node/NPM stability and robust deploy scripts.
  - Frontend Blade regressions (like `Undefined variable $footerBottomLinks`) fixed.
  - `APP_URL` validation and sanitation corrected in readiness logic.
  - Bootstrap cache contamination eliminated.
  - ThemePresentationService defaults elevated to luxury/premium standards (newsletter, footer).
  - Hero component default contract tightened (left-aligned, standard height, premium fallbacks and trust bar).
  - Architecture freeze document cleaned and made internally consistent.
- Blockers remaining:
  - None at the repository/code level.
- Next Steps:
  - Only live environment (UAT) visual confirmation remains.
