# Phase 4 Tasks — Page-Type Unification, Consultation Flow Upgrade, Luxury Copy Governance

## Locked context

- Single authoritative environment: `https://test.lushlandscape.ca`
- `LEGACY_STRICT=true` remains normal operating mode
- Presentation source of truth remains:
  - `page_blocks`
  - `theme_layouts`
  - `card_templates`
- No legacy authoring paths reopened
- No docs beyond this file (Phase 4)

## 1) Page-type unification (outliers aligned to governance model)

- [x] Service-city pages refactored into clean template-orchestrated unified page type (builder zones + fixed shell where required)
- [x] Contact page moved to template-orchestrated with builder zones
- [x] Consultation page (existing `/request-quote`) moved to template-orchestrated with builder zones
- [x] Blog post stays template-orchestrated; premium shell alignment improved
- [x] Portfolio project stays template-orchestrated; premium close + CTA alignment improved
- [x] Static longform pages governance tightened (builder-native vs template-orchestrated based on page purpose)
- [x] Search stays fixed template; premium shell alignment ensured

## 2) Consultation + contact experience upgrade (premium inquiry model)

- [x] `/request-quote` reframed as consultation/inquiry (no estimate-led framing)
- [x] Inquiry form taxonomy separated (property type vs project scope)
- [x] Verification + honeypot + queued notifications preserved
- [x] Success, consent, and email verification messaging upgraded

## 3) Luxury copy governance (defaults, not mass DB rewrites)

- [x] Remove estimate/quote-led defaults from:
  - seeders
  - blueprint services
  - theme presentation service defaults
  - frontend template CTAs
  - form default labels and submit text
  - helper-generated service-city block content
- [x] Establish consistent default CTA language across shell/pages/blocks

## 4) Tests (Phase 4 contracts)

- [x] Consultation page renders premium inquiry language and no quote-led defaults
- [x] Contact page aligns to consultation-led model and does not surface banned phrases
- [x] Service-city template remains orchestrated and does not surface banned phrases in defaults
- [x] Global default copy governance test prevents forbidden phrases in public-facing defaults

## Touched files (Phase 4)

- [x] [PHASE_4_TASKS.md](PHASE_4_TASKS.md)
- [x] [ContactController.php](../app/Http/Controllers/Frontend/ContactController.php)
- [x] [SingletonPageBuilderService.php](../app/Services/SingletonPageBuilderService.php)
- [x] [request-quote.blade.php](../resources/views/frontend/pages/request-quote.blade.php)
- [x] [contact.blade.php](../resources/views/frontend/pages/contact.blade.php)
- [x] [static.blade.php](../resources/views/frontend/pages/static.blade.php)
- [x] [mega-nav.blade.php](../resources/views/components/frontend/mega-nav.blade.php)
- [x] [app.blade.php](../resources/views/frontend/layouts/app.blade.php)
- [x] [process-steps.blade.php](../resources/views/components/frontend/process-steps.blade.php)
- [x] [interactive-map.blade.php](../resources/views/frontend/blocks/interactive-map.blade.php)
- [x] [service-area.blade.php](../resources/views/frontend/blocks/service-area.blade.php)
- [x] [city-grid.blade.php](../resources/views/components/frontend/city-grid.blade.php)
- [x] [HomePageBlueprintService.php](../app/Console/Services/HomePageBlueprintService.php)
- [x] [ListingPageBlueprintService.php](../app/Console/Services/ListingPageBlueprintService.php)
- [x] [ThemePresentationService.php](../app/Services/ThemePresentationService.php)
- [x] [SettingSeeder.php](../database/seeders/SettingSeeder.php)
- [x] [FormSeeder.php](../database/seeders/FormSeeder.php)
- [x] [MapContentSeeder.php](../database/seeders/MapContentSeeder.php)
- [x] [ServiceCategorySeeder.php](../database/seeders/ServiceCategorySeeder.php)
- [x] [CityContentSeeder.php](../database/seeders/CityContentSeeder.php)
- [x] [ContentBlockHelper.php](../database/seeders/Content/ContentBlockHelper.php)
- [x] [StaticPageContentSeeder.php](../database/seeders/StaticPageContentSeeder.php)
- [x] [FaqGeneralSeeder.php](../database/seeders/FaqGeneralSeeder.php)
- [x] [Phase4CopyGovernanceTest.php](../tests/Feature/Phase4CopyGovernanceTest.php)
- [x] [Phase3ContractsTest.php](../tests/Feature/Phase3ContractsTest.php)

## Deferred to Phase 5

- [x] None identified
