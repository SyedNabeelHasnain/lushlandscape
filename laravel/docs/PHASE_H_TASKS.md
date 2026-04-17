# Phase H: Contact, Consultation, and FAQ Page Family Implementation

This document tracks the final mapping and build-out of the trust, inquiry, and help page family (Contact, Consultation, and FAQ Index pages) using the approved Phase A-G unified FSE system.

## 1. Final Section-to-Block Mappings

### Contact Page (`/contact`)
*(Note: The main page shell and standard contact form are preserved to ensure reliable form submission and map rendering without converting the entire page to a free-form layout.)*
**Governed Builder Zones (Below Contact Section):**
1. **Common Inquiries**: `faq_section` (Provides quick answers to frequent questions to reduce unnecessary friction before contacting.)

### Consultation Page (`/request-quote` / `/consultation`)
*(Note: The main page shell, custom sidebar ("What Happens Next?"), and the advanced project inquiry form with custom logic are preserved for stability.)*
**Governed Builder Zones (Below Form):**
1. **What to Expect**: `process_steps` (Numbered process overview outlining the journey from consultation to project completion.)

### FAQ Landing Page (`/faqs`)
*(Note: The advanced custom filter logic and UI are preserved, but the surrounding layout has been converted to the unified system.)*
1. **FAQ Hero**: `parallax_media_band` (Overlay: dark, Parallax: subtle)
2. **FAQ Directory**: `faq_directory` (Wraps the custom grouped and filtered FAQ view into a managed block structure)
3. **Project Inquiry**: `split_consultation_panel` (Replaces the legacy hardcoded CTA with the unified consultation-led panel)

## 2. Execution Details

### What Was Built
- Added `faqs-index` to the `SingletonPageBuilderService` to allow the FAQ page to be governed by the FSE blocks.
- Updated `faqs.blade.php` to remove the hardcoded "Still Have Questions?" section and properly loop over FSE `$blocks`.
- Extended `ListingPageBlueprintService.php` to include `buildContact()`, `buildConsultation()`, and `buildFaqIndex()` methods.
- Registered the `faq_directory` block inside `config/blocks.php` to allow the custom FAQ loop to fit natively within the unified CMS architecture.
- Reused the `faq_section` and `process_steps` blocks as governed builder zones for the Contact and Consultation pages, providing reassurance without breaking the core form layouts.

### Files Touched
- `laravel/app/Services/SingletonPageBuilderService.php` (Registered `faqs-index`)
- `laravel/config/blocks.php` (Added `faq_directory`)
- `laravel/app/Console/Services/ListingPageBlueprintService.php` (Added Phase H mappings)
- `laravel/app/Http/Controllers/Frontend/FaqPageController.php` (Injected `$blocks` and `$context`)
- `laravel/resources/views/frontend/pages/faqs.blade.php` (Updated to render builder zones)
- `laravel/tests/Feature/PhaseHTrustBlueprintTest.php` (Created to mathematically prove mappings)

### What Was Verified
- **Shell Compatibility**: All three pages strictly adhere to the unified layout system without breaking existing form logic.
- **CTA Discipline**: Contact and FAQ pages use consultation-led phrasing ("Talk to our team directly", "Common Inquiries") instead of generic filler text.
- **Form Quality**: The complex OTP logic and specific field requirements on `/contact` and `/request-quote` remain untouched and functional.

## 3. Deferred Items
- Legal pages and machine-readable output refinements.
