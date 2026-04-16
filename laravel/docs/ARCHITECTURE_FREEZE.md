# Phase 1 — Architecture Freeze (Locked)

## Source of Truth (Locked)

Future first-class presentation architecture is:

- `page_blocks` (unified blocks)
- `theme_layouts` (global theme shells)
- `card_templates` (runtime cards via `template_card`)

Legacy systems are support-only (migration-only, non-authoritative, not valid for new work):

- `page_sections`
- `page_content_blocks`

## Runtime Presentation Architecture (What is active today)

### Unified storage + renderer

- Unified storage model: `page_blocks` via [PageBlock.php](../app/Models/PageBlock.php)
- Unified read API: [BlockBuilderService::getBlocks](../app/Services/BlockBuilderService.php)
- Unified admin edit API: [BlockBuilderService::getUnifiedBlocks](../app/Services/BlockBuilderService.php)
- Unified write API (authoritative): [BlockBuilderService::saveUnifiedBlocks](../app/Services/BlockBuilderService.php)
- Unified frontend rendering: `<x-frontend.block-renderer>` in [block-renderer.blade.php](../resources/views/components/frontend/block-renderer.blade.php)

### Theme shell (global header/footer)

The active header/footer path is theme-layout-driven and block-driven:

- Injection + caching path: [AppServiceProvider.php](../app/Providers/AppServiceProvider.php)
- Storage: `theme_layouts` via [ThemeLayout.php](../app/Models/ThemeLayout.php)

### Page context foundation

Normalized rendering context is produced by:

- [PageContextService.php](../app/Services/PageContextService.php)

## Builder Registries (Governance)

### Unified registry (authoritative)

- Unified block registry and style governance: [blocks.php](../config/blocks.php)

### Legacy registry (support-only)

- Legacy block registry (editor schemas): [content_blocks.php](../config/content_blocks.php)
- Compatibility service (support-only API surface): [ContentBlockService.php](../app/Services/ContentBlockService.php)

The unified builder/editor path uses `blocks.php` via:

- [BlockBuilderService::allTypes](../app/Services/BlockBuilderService.php)

## Page Governance Matrix (Derived from real code paths)

Governance levels:

- Fully builder-native
- Template-orchestrated with builder zones
- Fixed system template

| Page type | Primary controller | Primary view | Unified builder involvement | Future governance |
|---|---|---|---:|---|
| home (`/`) | Frontend: [HomeController.php](../app/Http/Controllers/Frontend/HomeController.php) | [home.blade.php](../resources/views/frontend/pages/home.blade.php) | Yes (`page_type=home`) | Fully builder-native |
| services hub (`/services`) | Frontend: [ServicePageController hub](../app/Http/Controllers/Frontend/ServicePageController.php) | [services-hub.blade.php](../resources/views/frontend/pages/services-hub.blade.php) | Yes (`page_type=services_hub`) | Fully builder-native |
| service category (`/services/{slug}`) | Frontend: [ServicePageController category](../app/Http/Controllers/Frontend/ServicePageController.php) | [service-category.blade.php](../resources/views/frontend/pages/service-category.blade.php) | Yes (`page_type=service_category`) | Fully builder-native |
| service detail (`/services/{categorySlug}/{slug}`) | Frontend: [ServicePageController detail](../app/Http/Controllers/Frontend/ServicePageController.php) | [service-detail.blade.php](../resources/views/frontend/pages/service-detail.blade.php) | Yes (`page_type=service`) | Fully builder-native |
| locations hub (`/locations`) | Frontend: [LocationPageController hub](../app/Http/Controllers/Frontend/LocationPageController.php) | [locations-hub.blade.php](../resources/views/frontend/pages/locations-hub.blade.php) | Yes (`page_type=locations_hub`) | Fully builder-native |
| city (`/landscaping-{slug}`) | Frontend: [LocationPageController city](../app/Http/Controllers/Frontend/LocationPageController.php) | [city.blade.php](../resources/views/frontend/pages/city.blade.php) | Yes (`page_type=city`) | Fully builder-native |
| blog index (`/blog`) | Frontend: [BlogController index](../app/Http/Controllers/Frontend/BlogController.php) | [blog-index.blade.php](../resources/views/frontend/pages/blog-index.blade.php) | Yes (`page_type=blog_index`) | Template-orchestrated with builder zones |
| blog category (`/blog/category/{slug}`) | Frontend: [BlogController category](../app/Http/Controllers/Frontend/BlogController.php) | [blog-category.blade.php](../resources/views/frontend/pages/blog-category.blade.php) | Yes (`page_type=blog_category`) | Template-orchestrated with builder zones |
| blog post (`/blog/{slug}`) | Frontend: [BlogController show](../app/Http/Controllers/Frontend/BlogController.php) | [blog-post.blade.php](../resources/views/frontend/pages/blog-post.blade.php) | Yes (`page_type=blog_post`) | Template-orchestrated with builder zones |
| portfolio index (`/portfolio`) | Frontend: [PortfolioController index](../app/Http/Controllers/Frontend/PortfolioController.php) | [portfolio.blade.php](../resources/views/frontend/pages/portfolio.blade.php) | Yes (`page_type=portfolio_index`) | Template-orchestrated with builder zones |
| portfolio category (`/portfolio/category/{slug}`) | Frontend: [PortfolioController category](../app/Http/Controllers/Frontend/PortfolioController.php) | [portfolio-category.blade.php](../resources/views/frontend/pages/portfolio-category.blade.php) | Yes (`page_type=portfolio_category`) | Template-orchestrated with builder zones |
| portfolio project (`/portfolio/{slug}`) | Frontend: [PortfolioController show](../app/Http/Controllers/Frontend/PortfolioController.php) | [portfolio-show.blade.php](../resources/views/frontend/pages/portfolio-show.blade.php) | Yes (`page_type=portfolio_project`) | Template-orchestrated with builder zones |
| contact (`/contact`) | Frontend: [ContactController show](../app/Http/Controllers/Frontend/ContactController.php) | [contact.blade.php](../resources/views/frontend/pages/contact.blade.php) | No (fixed template) | Template-orchestrated with builder zones |
| consultation (`/consultation` or `/request-quote`) | Frontend: [ContactController quote](../app/Http/Controllers/Frontend/ContactController.php) | [request-quote.blade.php](../resources/views/frontend/pages/request-quote.blade.php) | No (fixed template) | Template-orchestrated with builder zones |
| search (`/search`) | Frontend: [SearchController results](../app/Http/Controllers/Frontend/SearchController.php) | [search.blade.php](../resources/views/frontend/pages/search.blade.php) | No | Fixed system template |
| FAQ landing (`/faqs`) | Frontend: [FaqPageController index](../app/Http/Controllers/Frontend/FaqPageController.php) | [faqs.blade.php](../resources/views/frontend/pages/faqs.blade.php) | No (fixed template) | Fully builder-native |
| service-city (`/{slug}`) | Frontend: [SlugResolverController resolve](../app/Http/Controllers/Frontend/SlugResolverController.php) | [service-city.blade.php](../resources/views/frontend/pages/service-city.blade.php) | Yes (`page_type=service_city_page`) | Fully builder-native |
| static marketing (`/{slug}`) | Frontend: [SlugResolverController resolve](../app/Http/Controllers/Frontend/SlugResolverController.php) | [static.blade.php](../resources/views/frontend/pages/static.blade.php) | Yes (`page_type=static_page`) | Fully builder-native |
| machine-readable (`/sitemap.xml`, `/llms.txt`) | Frontend: [SitemapController.php](../app/Http/Controllers/Frontend/SitemapController.php), [LlmsTxtController.php](../app/Http/Controllers/Frontend/LlmsTxtController.php) | streamed | No | Fixed system template |

## Legacy Status (Reconciliation Summary)

### What remains active and why

- `page_sections` / `page_content_blocks` models still exist: [PageSection.php](../app/Models/PageSection.php), [ContentBlock.php](../app/Models/ContentBlock.php)
- Unified service still contains a conversion shim from legacy tables into `page_blocks` payloads:
  - [BlockBuilderService legacyCandidatePayloads](../app/Services/BlockBuilderService.php)

### What is now frozen (Phase 1 hard rules)

- No new authoring through legacy services or legacy registries.
- Unified blocks are mandatory for new presentation work.
- Legacy reads are support-only and must be surfaced.

## Block Registry Overlap (Buckets)

Authoritative registry is `blocks.php`. `content_blocks.php` is support-only.

`content_blocks.php` contains legacy-only types (no unified equivalent in `blocks.php` today). These are frozen; no new additions allowed:

- `container`, `carousel`, `video_embed`, `icon_grid`, `logo_grid`, `list`, `table`, `stats_row`, `pricing_table`, `comparison_table`, `timeline`, `cta_banner`, `testimonial_card`, `team_member`, `steps_process`, `badge_row`, `hero_banner`, `service_highlight`, `project_showcase`, `seasonal_info`, `area_served`, `rating_display`, `number_counter`, `progress_bars`, `map_embed`, `marquee`, `notice_bar`, `embed_code`, `interactive_map`, `service_area`, `service_hero`, `local_intro`, `portfolio_preview`, `benefits_grid`, `service_body`

## Import / Export Direction (Frozen)

Long-term import/export rule:

- Unified builder structures are the only first-class import/export direction.
- Legacy import/export remains only where required for migration/support.

Active import/export surfaces:

- Builder JSON export/import: [ContentBlockExportController.php](../app/Http/Controllers/Admin/ContentBlockExportController.php)
- DB table CSV export/import: [ImportExportController.php](../app/Http/Controllers/Admin/ImportExportController.php), [ExportService.php](../app/Services/ExportService.php), [ImportService.php](../app/Services/ImportService.php)
- Media JSON tooling: [BulkMediaImportController.php](../app/Http/Controllers/Admin/BulkMediaImportController.php)

## Theme Layouts + Card Templates (First-class)

These are long-term governed systems:

- `theme_layouts` + unified blocks (`page_type=theme_layout`)
- `card_templates` + unified blocks (`page_type=template_card`)

## Architectural Outliers (Tracked, not solved in Phase 1)

Outliers (hybrid/custom behaviors requiring governed handling later):

- consultation
- contact
- service-city
- static page (longform marketing variants)
- blog post
- portfolio project
- search

## Phase 1 Guardrails (Implemented)

- Legacy authoring paths are denied (writes).
- Legacy reads log warnings; strict mode can escalate on unified-authoritative page types.
- Admin editor shows a minimal banner when legacy markers exist in block payload.
