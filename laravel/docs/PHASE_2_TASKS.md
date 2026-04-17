# Phase 2 Tasks — Unified Registry, Builder Governance, Theme Shell Hardening

## Locked inputs (must remain true)

- Single environment: `https://test.lushlandscape.ca` is staging/testing/pre-prod/release-candidate.
- `LEGACY_STRICT=true` is normal operating mode there.
- Presentation source of truth: `page_blocks`, `theme_layouts`, `card_templates`.
- Legacy storage is support-only: `page_sections`, `page_content_blocks` (no new presentation work).
- Unified registry is the only evolving registry: `config/blocks.php`.

## 1) Unified registry consolidation (blocks.php becomes “governed components”)

### 1.1 Add governance metadata to unified block types

- Add governance keys per block type in [blocks.php](../config/blocks.php):
  - `governance.allowed_page_types`
  - `governance.variants`
  - `governance.required_fields`
  - `governance.supports_children_rules`
  - `governance.media_rules`
  - `governance.motion_rules`
  - `governance.fallback_behavior`

Implemented (initial baseline for premium-critical blocks):

- Added `governance.required_fields` for:
  - `hero`, `section_header`, `cards_grid`, `cta_section`, `form_block`, `dynamic_loop` ([blocks.php](../config/blocks.php))
- Section header CTA support:
  - `section_header` now supports optional CTA fields (`cta_text`, `cta_url`) ([blocks.php](../config/blocks.php))
  - rendering supports right-aligned CTA when applicable ([section-header.blade.php](../resources/views/frontend/blocks/section-header.blade.php))
- All block types now emit a normalized governance schema to the editor (even when config omits optional keys):
  - [BlockBuilderService::allTypes](../app/Services/BlockBuilderService.php)

Implemented (registry promotions from legacy-only set, required by seed/runtime):

- Added unified registry entries for:
  - `area_served`, `number_counter`, `interactive_map` ([blocks.php](../config/blocks.php))
  - `icon_grid`, `timeline`, `steps_process`, `testimonial_card` ([blocks.php](../config/blocks.php))
  - `cta_banner` (marked transitional) ([blocks.php](../config/blocks.php))

### 1.2 Promote strategically necessary legacy-only concepts into unified registry

Legacy-only types currently present only in [content_blocks.php](../config/content_blocks.php) are frozen and must not grow. Promote only what is strategically required for the locked luxury frontend direction.

Carry-forward candidates (must be resolved into unified blocks or governed variants):

- `cta_banner` → unified variant of `cta_section` (consultation-led, contrast-safe)
- `service_hero` / `hero_banner` → governed `hero` variants (one-viewport, media background, trust row, CTA row)
- `portfolio_preview` / `project_showcase` → governed selected-work block (grid/controlled carousel)
- `steps_process` → governed `process_steps` variants (restrained, editorial)
- `service_area` / `area_served` → governed service-area text-led block variant
- `interactive_map` / `map_embed` → unify into a governed map block (no html embed defaulting)
- `testimonial_card` → unify under a governed testimonial/card pattern (or template_card family)

Retire candidates (do not promote; keep as legacy support-only):

- `embed_code` and other generic escape hatches unless a strict need remains

## 2) Builder/runtime enforcement (governance is not advisory)

### 2.1 Enforce page eligibility at save time (backend authoritative)

- Implement validation in [BlockBuilderService.php](../app/Services/BlockBuilderService.php) inside `saveUnifiedBlocks()` / `persistUnifiedBlocks()`:
  - deny blocks not allowed on a page type (governance rule)
  - validate required fields by block variant
  - validate child rules (layout containers can require `_layout_slot` or enforce max children)

Implemented:

- Added backend validator: [BlockGovernanceService.php](../app/Services/BlockGovernanceService.php)
- Enforced validation at save entry points:
  - [BlockBuilderService::saveUnifiedBlocks](../app/Services/BlockBuilderService.php)
  - [BlockBuilderService::saveBlocks](../app/Services/BlockBuilderService.php)
- Enforced theme-block isolation (theme blocks allowed only on `theme_layout`) via backend validator.

### 2.2 Tighten editor behavior (minimal UI changes, strict outcomes)

- Update block editor JS in [admin.js](../resources/js/admin.js):
  - variant-aware field visibility (hide irrelevant fields)
  - inline required-field validation for premium-critical blocks (submit should fail client-side too)
  - preserve Phase 1 legacy banner behavior (already in [block-editor.blade.php](../resources/views/components/admin/block-editor.blade.php))

Implemented (initial baseline):

- Block picker filters out theme blocks for non-theme pages and honors `governance.allowed_page_types` when provided:
  - [block-editor.blade.php](../resources/views/components/admin/block-editor.blade.php)
  - [admin.js](../resources/js/admin.js)

## 3) Theme shell hardening (consultation-led defaults)

### 3.1 Remove quote-led shell defaults

Replace `/consultation` as the default CTA target in these sources:

- [blocks.php](../config/blocks.php) `hero.defaults.cta_primary_url`
- [blocks.php](../config/blocks.php) `theme_cta_group.defaults.primary_url`
- [ThemePresentationService.php](../app/Services/ThemePresentationService.php) fallback for `ctaUrl()`
- Any legacy CTA defaults still referenced by seeders/config (transitional marking only)

New default must be consultation-led and must use the existing `/contact` route (do not invent new routes in Phase 2).

Implemented:

- `hero.defaults.cta_primary_url` → `/contact` ([blocks.php](../config/blocks.php))
- `theme_cta_group.defaults.primary_url` → `/contact` ([blocks.php](../config/blocks.php))
- `cta_section.defaults.button_url` → `/contact` ([blocks.php](../config/blocks.php))
- `ThemePresentationService::ctaText()` default → “Book a Consultation” ([ThemePresentationService.php](../app/Services/ThemePresentationService.php))
- `ThemePresentationService::ctaUrl()` default → `/contact` ([ThemePresentationService.php](../app/Services/ThemePresentationService.php))
- Seeder defaults:
  - `nav_cta_text` → “Book a Consultation” ([SettingSeeder.php](../database/seeders/SettingSeeder.php))
  - `nav_cta_url` → `/contact` ([SettingSeeder.php](../database/seeders/SettingSeeder.php))
  - `announcement_bar_url` → `/contact` ([SettingSeeder.php](../database/seeders/SettingSeeder.php))
  - `footer_tagline` contractor wording removed in default seed ([SettingSeeder.php](../database/seeders/SettingSeeder.php))

### 3.2 Harden theme layout blueprint outputs

- Update [ThemeLayoutBlueprintService.php](../app/Console/Services/ThemeLayoutBlueprintService.php) to output:
  - consultation-led CTA defaults
  - header variants: transparent hero header / glass / solid light / solid dark
  - footer defaults: restrained, no contractor copy defaults

Implemented:

- Header variants scaffolded as drafts:
  - [ThemeLayoutBlueprintService.php](../app/Console/Services/ThemeLayoutBlueprintService.php) `HEADER_LAYOUT_VARIANTS`
- Header CTA defaults sourced from unified registry defaults (not live settings):
  - [ThemeLayoutBlueprintService.php](../app/Console/Services/ThemeLayoutBlueprintService.php)
- Footer hardened:
  - footer logo tagline disabled in scaffold
  - contact strip uses restrained defaults (no email/hours)
  - [ThemeLayoutBlueprintService.php](../app/Console/Services/ThemeLayoutBlueprintService.php)

## 4) Blueprint alignment (no contradictions)

Update blueprint generators so new scaffolds always generate governed unified blocks and never legacy-first patterns:

- [HomePageBlueprintService.php](../app/Console/Services/HomePageBlueprintService.php)
- [ListingPageBlueprintService.php](../app/Console/Services/ListingPageBlueprintService.php)
- [ThemeLayoutBlueprintService.php](../app/Console/Services/ThemeLayoutBlueprintService.php)

Minimum corrections:

- remove quote-led CTAs from generated payloads
- generate the new governed hero + section header + category grid + selected work + consultation blocks
- avoid emitting block types that exist only in `content_blocks.php`

Implemented:

- Blueprint CTA targets updated from `/consultation` → `/contact`:
  - [HomePageBlueprintService.php](../app/Console/Services/HomePageBlueprintService.php)
  - [ListingPageBlueprintService.php](../app/Console/Services/ListingPageBlueprintService.php)

Implemented (seeders and scaffolds no longer use legacy authoring APIs):

- Seeders now write unified `page_blocks` via `BlockBuilderService::saveUnifiedBlocks()`:
  - [HomePageContentSeeder.php](../database/seeders/HomePageContentSeeder.php)
  - [StaticPageContentSeeder.php](../database/seeders/StaticPageContentSeeder.php)
  - [MapContentSeeder.php](../database/seeders/MapContentSeeder.php)
  - [PortfolioSeeder.php](../database/seeders/PortfolioSeeder.php)
  - [ContentBlockHelper.php](../database/seeders/Content/ContentBlockHelper.php)

## 5) Card template system governance (first-class design layer)

### 5.1 Define card families (governed)

- Implement a controlled set of card families in `card_templates` (through existing admin editing):
  - category cards
  - service cards
  - project cards
  - city cards
  - related content cards

Implemented (scaffolded starter families):

- Added `template_card_shell` block for template-card design ([blocks.php](../config/blocks.php), [template-card-shell.blade.php](../resources/views/frontend/blocks/template-card-shell.blade.php))
- Added core template families scaffold:
  - [CardTemplateBlueprintService.php](../app/Console/Services/CardTemplateBlueprintService.php)
  - [ScaffoldCardTemplates.php](../app/Console/Commands/ScaffoldCardTemplates.php)
  - Command: `php artisan app:scaffold-card-templates --activate`
  - Test: [CardTemplateBlueprintServiceTest.php](../tests/Feature/CardTemplateBlueprintServiceTest.php)

### 5.2 Safer runtime fallback behavior

- Improve dynamic loop fallback rendering in:
  - [dynamic-loop.blade.php](../resources/views/frontend/blocks/partials/dynamic-loop.blade.php)

Fallback must be premium-safe (either a restrained default card or an explicit “missing template” indicator in admin/preview contexts).

Implemented:

- Dynamic loop fallback updated to a premium-safe minimal card and shows an admin-only indicator when `template_id` is missing:
  - [dynamic-loop.blade.php](../resources/views/frontend/blocks/partials/dynamic-loop.blade.php)

## 6) Tests (Phase 2 must be provable)

Add/extend tests to protect Phase 2 governance:

- Registry validation:
  - “no new legacy-only block types added”
  - “governance keys exist for premium-critical blocks”
- Governance enforcement:
  - save-time denial when block used on wrong page type
  - required-field validation by variant
- Shell defaults:
  - verify default CTA is not `/consultation`
- Blueprint outputs:
  - generated home/listing/theme layouts use governed blocks and consultation-led CTAs
- Card templates:
  - dynamic loop fallback is premium-safe and deterministic

Implemented (initial baseline):

- Governance tests:
  - [Phase2GovernanceTest.php](../tests/Feature/Phase2GovernanceTest.php)
  - [Phase2RegistryTest.php](../tests/Feature/Phase2RegistryTest.php)

## 7) Deferred (Phase 3+)

- Motion governance + curated animation presets and reduced-motion mapping.
- Media art direction system upgrades and template card refinement.
- Page-type wide copy governance and premium tone enforcement beyond defaults.
