# Phase 3 Tasks — Media Art Direction, Card Templates, Responsive Behavior, Motion Governance

## Locked context

- Single authoritative environment: `https://test.lushlandscape.ca`
- `LEGACY_STRICT=true` remains normal operating mode
- Presentation source of truth remains:
  - `page_blocks`
  - `theme_layouts`
  - `card_templates`
- No new legacy authoring paths
- No additional docs beyond this file (Phase 3)

## 1) Media art direction (placement-aware where it matters)

- [x] Audit current premium blocks that render media (hero, editorial split, cards, galleries)
- [x] Introduce a shared frontend media renderer that can use:
  - focal point
  - crop data (when available)
  - alt/caption overrides
  - loading + fetchpriority
- [x] Shared frontend media renderer added (focal point + placement overrides supported)
- [x] Apply to:
  - [x] hero (image + slider)
  - [x] editorial split feature
  - [x] selected-work / portfolio gallery
  - [x] category/service/project/city cards where applicable (cards_grid)

## 2) Card-template system becomes design-system layer

- [x] Formalize card families in `card_templates` (service/category/city/project/post/related/value/process)
- [x] Make runtime fallback premium-safe and admin-visible
- [x] Ensure `dynamic_loop` supports family-grade templates cleanly

## 3) Component-aware responsive behavior

- [x] Implement structural responsive composition changes (not only CSS shrinking) for:
  - [x] hero
  - [x] section_header
  - [x] cards_grid
  - [x] image_text
  - [x] editorial_split_feature
  - [x] portfolio_gallery (selected work)
  - [x] process_steps
  - [x] city_grid (or premium text-led alternative)
  - [x] cta_section
  - [x] form_block
  - [x] dynamic_loop

## 4) Motion + interaction governance (preset layer)

- [x] Extend motion preset system to support “no motion”
- [x] Govern hero slider autoplay and background video behavior under reduced motion
- [x] Ensure block-level animations remain curated (no random patterns)

## 5) Hero system upgrade (one-viewport, art-directed, luxury-safe)

- [x] Overlay presets + overlay opacity support (field-backed)
- [x] Focal-point aware image cropping
- [x] Controlled heading scale + paragraph width
- [x] Consultation-led defaults across all hero surfaces (no `/request-quote` fallback)

## 6) Category grid + selected work upgrades

- [x] Category grid supports 2x2 luxury mode + mobile composition
- [x] Selected work supports compact cards + CTA alignment + grid/carousel governed modes

## 7) Service-area presentation upgrade

- [x] Add premium text-led service-area mode (inline separators, controlled typography)

## 8) Consultation entry upgrade (presentation only)

- [x] CTA / consultation blocks are contrast-safe and composition-aware

## 9) Editor support (only where required)

- [x] Media focal/crop controls surfaced where needed
- [x] Motion preset selectors where needed
- [x] Responsive mode selectors for key blocks where needed

## 10) Tests (Phase 3 contracts)

- [x] Media rendering contracts (focal point + alt/priority)
- [x] Hero contract (overlay + reduced motion behavior)
- [x] Card-template family/fallback contract
- [x] Responsive mode contracts where testable
- [x] Motion preset safety contract

## Touched files (Phase 3)

- [x] [PHASE_3_TASKS.md](PHASE_3_TASKS.md)
- [x] [media.blade.php](../resources/views/components/frontend/media.blade.php)
- [x] [hero.blade.php](../resources/views/components/frontend/hero.blade.php)
- [x] [hero.blade.php](../resources/views/frontend/blocks/partials/hero.blade.php)
- [x] [hero.blade.php](../resources/views/frontend/sections/hero.blade.php)
- [x] [service-hero.blade.php](../resources/views/frontend/sections/service-hero.blade.php)
- [x] [scp-hero.blade.php](../resources/views/frontend/sections/scp-hero.blade.php)
- [x] [blocks.php](../config/blocks.php)
- [x] [app.js](../resources/js/app.js)
- [x] [editorial-split-feature.blade.php](../resources/views/frontend/blocks/editorial-split-feature.blade.php)
- [x] [cards-grid.blade.php](../resources/views/frontend/blocks/cards-grid.blade.php)
- [x] [image-text.blade.php](../resources/views/frontend/blocks/image-text.blade.php)
- [x] [portfolio-gallery.blade.php](../resources/views/frontend/blocks/partials/portfolio-gallery.blade.php)
- [x] [_portfolio-card.blade.php](../resources/views/frontend/blocks/partials/_portfolio-card.blade.php)
- [x] [cta-section.blade.php](../resources/views/frontend/blocks/partials/cta-section.blade.php)
- [x] [city-grid.blade.php](../resources/views/frontend/blocks/partials/city-grid.blade.php)
- [x] [dynamic-loop.blade.php](../resources/views/frontend/blocks/partials/dynamic-loop.blade.php)
- [x] [area-served.blade.php](../resources/views/frontend/blocks/area-served.blade.php)
- [x] [blog-strip.blade.php](../resources/views/frontend/blocks/partials/blog-strip.blade.php)
- [x] [portfolio-directory.blade.php](../resources/views/frontend/blocks/partials/portfolio-directory.blade.php)
- [x] [service-highlight.blade.php](../resources/views/frontend/blocks/service-highlight.blade.php)
- [x] [project-showcase.blade.php](../resources/views/frontend/blocks/project-showcase.blade.php)
- [x] [hero-banner.blade.php](../resources/views/frontend/blocks/hero-banner.blade.php)
- [x] [image.blade.php](../resources/views/frontend/blocks/image.blade.php)
- [x] [image.blade.php](../resources/views/frontend/blocks/partials/image.blade.php)
- [x] [gallery.blade.php](../resources/views/frontend/blocks/gallery.blade.php)
- [x] [gallery.blade.php](../resources/views/frontend/blocks/partials/gallery.blade.php)
- [x] [Phase3ContractsTest.php](../tests/Feature/Phase3ContractsTest.php)
- [x] [CardTemplateBlueprintService.php](../app/Console/Services/CardTemplateBlueprintService.php)
- [x] [CardTemplateBlueprintServiceTest.php](../tests/Feature/CardTemplateBlueprintServiceTest.php)
- [x] [app.blade.php](../resources/views/frontend/layouts/app.blade.php)
- [x] [cta-section.blade.php](../resources/views/components/frontend/cta-section.blade.php)
- [x] [cta-section.blade.php](../resources/views/frontend/sections/cta-section.blade.php)
- [x] [service-body.blade.php](../resources/views/frontend/sections/service-body.blade.php)
- [x] [service-city.blade.php](../resources/views/frontend/pages/service-city.blade.php)
- [x] [portfolio-show.blade.php](../resources/views/frontend/pages/portfolio-show.blade.php)
- [x] [blog-post.blade.php](../resources/views/frontend/pages/blog-post.blade.php)
- [x] [contact.blade.php](../resources/views/frontend/pages/contact.blade.php)
- [x] [faqs.blade.php](../resources/views/frontend/pages/faqs.blade.php)
- [x] [settings/index.blade.php](../resources/views/admin/settings/index.blade.php)
- [x] [MediaAssetController.php](../app/Http/Controllers/Admin/MediaAssetController.php)
- [x] [media/form.blade.php](../resources/views/admin/media/form.blade.php)

## Deferred to Phase 4

- [x] None identified
