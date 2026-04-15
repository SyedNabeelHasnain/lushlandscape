# Phase 1 Tasks — Architecture Freeze Implementation

## Locked rules (Phase 1 and beyond)

- Unified presentation is authoritative: `page_blocks` + `theme_layouts` + `card_templates`
- Legacy is support-only: `page_sections` + `page_content_blocks`
- From Phase 1 onward:
  - No new page may be authored on `page_sections`
  - No new page may be authored on `page_content_blocks`
  - No new block may be added only to `config/content_blocks.php`
  - No new presentation feature may be built on the legacy path first

## Completed (Phase 1)

### Docs (required deliverables only)

- Added [ARCHITECTURE_FREEZE.md](ARCHITECTURE_FREEZE.md)
- Added [PHASE_1_TASKS.md](PHASE_1_TASKS.md)
- Page governance matrix updated from actual route/controller/view graph: [web.php](../routes/web.php)

### Guardrails (stop legacy drift)

- Added legacy governance service: [LegacyGovernanceService.php](../app/Services/LegacyGovernanceService.php)
  - `LEGACY_STRICT` support
  - legacy reads: warning logs + strict escalation for unified-authoritative page types
  - legacy writes: hard deny with exception
- Denied legacy authoring path:
  - [ContentBlockService.php](../app/Services/ContentBlockService.php) `saveBlocks()` now fails hard
  - [BlockBuilderService.php](../app/Services/BlockBuilderService.php) `saveLegacyBlocks()` now fails hard
- Added legacy read telemetry on legacy-table read shim:
  - [BlockBuilderService.php](../app/Services/BlockBuilderService.php) `legacyCandidatePayloads()` logs legacy read usage and can fail in strict mode
- Declared strict page-type contract for unified-authoritative surfaces:
  - [blocks.php](../config/blocks.php) `strict_unified_page_types`

### Migration support (legacy reconciliation tooling)

- Implemented legacy reconciliation inventory and backfill primitives:
  - [BlockBuilderService.php](../app/Services/BlockBuilderService.php) `legacyPageInventory()`, `ensureLegacyBackfilled()`, `missingLegacyBlockCount()`
- Added console migration-support commands:
  - `blocks:sync-legacy` and `blocks:prune-legacy` in [console.php](../routes/console.php)

### Admin UX (minimal editor banner)

- Added a minimal admin-only banner when legacy markers are present in loaded block payload:
  - [block-editor.blade.php](../resources/views/components/admin/block-editor.blade.php)

### Tests (drift prevention)

- Added Phase 1 freeze tests:
  - [ArchitectureFreezeTest.php](../tests/Feature/ArchitectureFreezeTest.php)
  - Ensures no unapproved additions to `config/content_blocks.php` legacy-only registry
  - Ensures legacy authoring APIs are denied

## Deferred (Phase 2+)

- Remove unused legacy conversion shim in `BlockBuilderService` after confirmed migration and parity.
- Unify or retire legacy-only block concepts listed in ARCHITECTURE_FREEZE.md block overlap section.
- Add governed “builder zones” for template-orchestrated page types (service-city, blog post, portfolio project, contact, request quote).
- Formalize card-template governance rules (fallback policy, required fields, allowed loop models).
- Replace any remaining legacy-backed runtime behavior with unified equivalents (if discovered during Phase 2 audits).

## Strict-mode survivability checklist (test.lushlandscape.ca)

- Runbook: [STAGING_VERIFICATION_RUNBOOK.md](STAGING_VERIFICATION_RUNBOOK.md)

- [ ] `LEGACY_STRICT=true` enabled
- [ ] No legacy authoring flows exist in admin UI
- [ ] No legacy-only registrations added to `config/content_blocks.php`
- [ ] Legacy reads are surfaced and reduced over time, especially for unified-authoritative page types
