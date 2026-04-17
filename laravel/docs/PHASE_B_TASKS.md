# Phase B: Premium Block System Expansion, Advanced Section Families, and Home-First Capability Buildout

This document serves as the formal closure record for Phase B. It outlines the new premium section families added to the Unified Block Registry, the card families strengthened, the media-aware behavior integrated, and the capabilities now available to build the locked homepage natively through the CMS.

## Phase B Corrective Closure
A corrective pass was executed to address inconsistencies between what was claimed and what was fully integrated into the authoritative unified registry.

**What was originally intended:**
Phase B intended to introduce new premium blocks (`marquee_strip`, `parallax_media_band`, `authority_grid`, `service_area_enclave`, `split_consultation_panel`) and corresponding controls to the unified builder, while avoiding quote-led language or mocked functionality.

**What was actually missing or incorrect:**
- Missing default values for some new block controls in `config/blocks.php` (e.g., `show_icon`, `show_divider`, `media_id`), leading to potential ghost fields.
- The `split_consultation_panel` still contained quote-led language ("No obligation quote", "Request Your Quote") and a hardcoded, mocked HTML form rather than connecting to the real form system.
- The tests did not verify the existence of the new controls or premium variants.

**What was corrected in this closure pass:**
- **Registry Alignment:** Ensured all premium block controls (`parallax_intensity`, `separator_style`, `presentation_mode`, `show_icon`, `show_divider`, `show_usp_list`, `card_cta_label`) have proper default values and are fully authorable via the unified builder.
- **Consultation Panel Realism:** Removed all quote-led and estimate-led wording from `split_consultation_panel`. Replaced the mocked form structure with the system's real reusable form contract (`_form-fields.blade.php`), using a dynamically selected `form_slug` that defaults to `contact-us`.
- **Enclave Separators:** Replaced temporary text-joining slash characters with governed bullet dots and structured flex gaps for the `service_area_enclave` inline text-led mode.
- **Test Integrity:** Expanded `BlockBuilderServiceRegistryTest.php` to explicitly verify that the premium controls and variants (`premium-2x2`, `rail`, `premium-stack`, `title-only`, `with-right-cta`, `full-editorial`) exist in the registry and that the consultation panel is consultation-led.

## 1. Premium Section Families Identified & Added
Based on the reference design language and Phase A deferrals, the following advanced premium sections were added as first-class unified blocks in `config/blocks.php`:

- **`marquee_strip`**: A governed, seamlessly looping marquee block. Supports repeated text sequences, separator style controls (dot, star, line, none), speed presets, direction toggles, and light/dark/forest tone compatibility.
- **`parallax_media_band`**: A cinematic media section. Supports image or video backgrounds, headline/subheadline overlays, parallax intensity controls (none, subtle, medium, strong), and overlay presets (dark, light, forest).
- **`authority_grid`**: A composed standards section. Supports an eyebrow, heading, introduction, and a repeater for authority items (icon, title, short description). Includes premium card skins (`premium-bordered`, `elevated`, `minimal`).
- **`service_area_enclave`**: A premium presentation for service areas/cities. Supports a `text-led` mode for elegant inline lists and a `tabbed-enclave` mode for a split layout with interactive, hover-safe city cards.
- **`split_consultation_panel`**: A highly specific split layout. Features a left-side editorial panel with trust lines and optional background media (mix-blend overlay), and a right-side consultation form area that delegates to the real `_form-fields.blade.php` contract to ensure actual data handling.

## 2. Existing Blocks Enhanced with Premium Variants
- **`section_header`**: Expanded variants to include `title-only`, `with-right-cta`, and `full-editorial`. This removes the need for ad hoc heading setups and provides a standard heading shell for premium sections.
- **`services_grid`**: Added a `premium-2x2` variant. Added dedicated block-level controls for `show_icon`, `show_divider`, `show_usp_list`, and `card_cta_label` to achieve the exact audience-facing copy rhythm needed for the homepage.
- **`portfolio_gallery`**: Added a `rail` (Horizontal Rail) layout option to support horizontal-track scrolling of selected-work cards, complete with hidden scrollbars and snap-mandatory behavior.
- **`process_steps`**: Added a `premium-stack` variant. This replaces generic numbered lists with a stacked, shadow-elevated card presentation featuring phase numbers, side-by-side layout, and strong progression rhythm.

## 3. Card Families Strengthened
- **Service Cards (`service-card.blade.php`)**: Re-architected as a robust, reusable Blade component (`<x-frontend.service-card>`). 
  - Standardized aspect ratios (`aspect-16/10` vs `aspect-4/3`).
  - Added strict line-clamp rules for headings and support copy.
  - Implemented the `premium-2x2` skin (soft elevation, cream backgrounds, quiet hover lifts).
  - Ensured safe fallback behaviors if media or icons are missing (e.g., gradient placeholders with faded icons).
- **Project Cards (`_portfolio-card.blade.php`)**: Hardened with strict `line-clamp-1` rules for titles and meta information, ensuring uniform heights in the new horizontal `rail` layout.

## 4. Builder / Editor Controls Added
Added governed fields to `config/blocks.php` to expose these new capabilities without allowing unmanaged visual chaos:
- `parallax_intensity` (None, Subtle, Medium, Strong)
- `separator_style` (Dot, Star, Line) for marquees
- `presentation_mode` (Text-Led, Tabbed Enclave)
- `card_cta_label` for dynamic text control on service cards
- `show_divider`, `show_icon` toggles for strict visual rhythm

## 5. Media-Aware Behavior Added
- Integrated `<video autoplay loop muted playsinline>` support directly into the `parallax_media_band`, with fallback to static `<img>` or solid color if media is absent.
- Applied `mix-blend-overlay` and low opacity to media within the `split_consultation_panel` to create rich, textured backgrounds without sacrificing text legibility.
- Governed parallax scaling (`scale-105`, `scale-110`, `scale-125`) tied to CMS intensity controls.

## 6. Touched File List
1. `laravel/config/blocks.php`
2. `laravel/resources/css/app.css` (Added `.hide-scrollbar`)
3. `laravel/resources/views/components/frontend/service-card.blade.php` (Major upgrade)
4. `laravel/resources/views/frontend/blocks/partials/section-header.blade.php`
5. `laravel/resources/views/frontend/blocks/partials/services-grid.blade.php`
6. `laravel/resources/views/frontend/blocks/partials/portfolio-gallery.blade.php`
7. `laravel/resources/views/frontend/blocks/partials/process-steps.blade.php`
8. `laravel/resources/views/frontend/blocks/marquee-strip.blade.php` (New)
9. `laravel/resources/views/frontend/blocks/parallax-media-band.blade.php` (New)
10. `laravel/resources/views/frontend/blocks/authority-grid.blade.php` (New)
11. `laravel/resources/views/frontend/blocks/service-area-enclave.blade.php` (New)
12. `laravel/resources/views/frontend/blocks/split-consultation-panel.blade.php` (New)
13. `laravel/tests/Feature/BlockBuilderServiceRegistryTest.php`
14. `laravel/docs/PHASE_B_TASKS.md` (This file)

## 7. What is Now Possible Because of Phase B
The platform is now fully capable of composing the locked homepage design natively. Editors can sequence a `parallax_media_band`, transition into a `premium-2x2` `services_grid`, display a `rail` of `portfolio_gallery` projects, highlight standards via the `authority_grid`, and close with the `split_consultation_panel`—all without writing a single line of custom HTML or breaking out of the unified block registry. The system is additive, backward-compatible, and entirely CMS-driven.

## 8. Intentionally Deferred to Phase C
- **Actual Page Construction:** We have built the *capabilities* and *components*, but we have not yet populated the actual database records or `page_blocks` payloads to stitch the homepage together.
- **Mass Content Migration:** Moving legacy content into these new premium blocks.
- **Dynamic FSE Layout Application:** Applying these blocks specifically into the FSE `theme_layouts` (like the default header/footer).