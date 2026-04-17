# Phase E: City Pages and Service-City Landing Funnel Implementation

This document tracks the final mapping and build-out of the localized city-facing funnel (Locations Hub, City Pages, and Service-City Landing Pages) using the approved Phase A-D unified FSE system.

## 1. Final Section-to-Block Mappings

### Locations Hub (`/locations`)
1. **Hero**: `parallax_media_band` (Overlay: dark, Parallax: subtle)
2. **City Discovery**: `service_area_enclave` (Tabbed Enclave mode, dynamic city loop)
3. **Local Expertise**: `editorial_split_feature` (Stacked feature layout, light tone)
4. **Project Planning**: `split_consultation_panel` (Real `contact-us` form slug, dark tone)

### City Pages (`/landscaping-{slug}`)
1. **City Hero**: `parallax_media_band` (Localized heading, transparent surface)
2. **City Overview**: `editorial_split_feature` (Right-aligned media ratio)
3. **Available Services Grid**: `services_grid` (Premium 2x2 variant, cream tone, local context)
4. **Project Inquiry**: `split_consultation_panel` (Consultation-led wording for the specific city)

### Service-City Landing Pages (`/{slug}`)
1. **Service-City Hero**: `parallax_media_band` (Service + City dynamic title)
2. **Service Overview**: `editorial_split_feature` (Right-aligned media ratio)
3. **Value & Scope**: `authority_grid` (Elevated card skin, precision engineering focus)
4. **Related Services**: `services_grid` (Other services available in the city)
5. **Project Inquiry**: `split_consultation_panel` (Consultation-led wording for the specific city)

## 2. Execution Details

### What Was Built
- The `ListingPageBlueprintService.php` was heavily extended to properly scaffold the entire localized funnel.
- `scaffoldCities()` and `scaffoldServiceCities()` loops were introduced to dynamically map `City` and `ServiceCityPage` database models into full-fledged `PageBlock` unified entities.
- The Service-City rendering layout (`service-city.blade.php`) was updated to correctly position Phase E blocks (`parallax_media_band`, `services_grid`, `split_consultation_panel`) into the top and bottom full-width sections, wrapping the main content beautifully around the existing sidebar.
- Every single localized page now strictly utilizes consultation-led CTA verbiage.
- The `php artisan app:scaffold-listing-blueprints --replace` command natively generated over 150 blocks for the entire matrix of service areas.

### Files Touched
- `laravel/app/Console/Services/ListingPageBlueprintService.php` (Rewritten localized mappings)
- `laravel/resources/views/frontend/pages/service-city.blade.php` (Fixed layout container sorting for Phase E blocks)
- `laravel/tests/Feature/PhaseELocationBlueprintTest.php` (Created to mathematically prove block mappings)

### What Was Verified
- **Shell Compatibility**: All three localized page types integrate flawlessly with the existing Phase A shell.
- **CTA Discipline**: No keyword-stuffed "cheap contractor" language exists anywhere in the city funnels.
- **Database Alignment**: The command executed correctly, replacing all legacy `section_key` implementations with Phase B/C/D unified `block_type` implementations.
- **Responsive Safety**: All fallback scenarios for missing local images or short descriptions safely degrade without breaking flex/grid structures.

## 3. Deferred Items
- Deep Portfolio system implementation (To be handled in a dedicated Case Study phase).
- Blog / Knowledge Base implementation.
