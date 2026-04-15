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
- [ ] Page type rendering validated (see sweep table below)
- [ ] Shell consistency validated (header/footer + CTA model)
- [ ] Builder/editor flows validated (block editor load/save, nested blocks, templates, media)
- [ ] Consultation/contact flows validated (rendering, OTP, submission, success messaging)
- [x] No critical JS / console / application errors during normal use (public pages)

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
- [ ] Missing optional content fields do not produce broken spacing/typography

## 4) Performance + caching hardening

- [x] Block rendering overhead reviewed (no avoidable repeated work in hot paths)
- [ ] Dynamic loop overhead reviewed (template blocks cached safely)
- [ ] Cache invalidation safe for:
  - page blocks
  - theme layouts
  - card templates
  - nav/footer taxonomy lists

## 5) Observability + release safety

- [ ] Strict-mode failures produce actionable errors (not noisy)
- [x] Readiness + smoke commands reflect Phase 5 criteria

## 6) Tests (Phase 5 contracts)

- [x] Strict mode behavior test added/updated
- [x] Cache invalidation behavior hardened (where safe to test)
- [x] Readiness check extended to cover strict mode
- [ ] Smoke audit covers premium-critical routes (public + admin where available)

## Validation sweep log (Phase 5)

| Surface | Status | Notes |
| --- | --- | --- |
| Home | Failed | `https://test.lushlandscape.ca/` still surfaces quote-led CTA language (nav/footer) and legacy form taxonomy; indicates environment not updated to latest code+defaults. |
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
| Consultation | Failed | `https://test.lushlandscape.ca/request-quote` still renders “Request a Quote / Free estimate / no obligation” language and old field taxonomy; indicates environment not updated. |
| Search |  |  |
| FAQ |  |  |
| Legal pages |  |  |

## Touched files (Phase 5)

- [x] [PHASE_5_TASKS.md](PHASE_5_TASKS.md)
- [x] [ThemePresentationService.php](../app/Services/ThemePresentationService.php)
- [x] [BlockGovernanceService.php](../app/Services/BlockGovernanceService.php)
- [x] [ProductionReadinessCheck.php](../app/Console/Commands/ProductionReadinessCheck.php)
- [x] [RuntimeSmokeAudit.php](../app/Console/Commands/RuntimeSmokeAudit.php)
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
- [x] [app.blade.php](../resources/views/frontend/layouts/app.blade.php)
- [x] [editorial-split-feature.blade.php](../resources/views/frontend/blocks/editorial-split-feature.blade.php)

## Release readiness status

- Status: **Not ready**
- Blockers (exact):
  - Staging environment `test.lushlandscape.ca` appears out of sync with the Phase 4/5 code+defaults (quote-led CTA language + old inquiry taxonomy still visible on Home and Consultation pages).
  - Admin/editor validation and smoke-audit cannot be completed until the authoritative environment is updated to the current code and caches are cleared.
