# Staging Verification Runbook (Phase 1 Strict-Mode)

Target: `https://test.lushlandscape.ca`

Goal: verify Phase 1 “architecture freeze” guardrails behave correctly under normal staging operating mode (`LEGACY_STRICT=true`) and that no legacy-authoring paths remain available.

## 0) Preconditions

- You can deploy to `test.lushlandscape.ca` and set environment variables.
- You have an admin account on staging.

## 1) Enable strict mode

- Set environment:
  - `LEGACY_STRICT=true`
  - `APP_ENV=production`
  - `APP_DEBUG=false`

## 2) Confirm legacy authoring is blocked (hard deny)

In staging admin:

- Open any block editor route (examples):
  - `/admin/blocks/home/0`
  - `/admin/blocks/service_category/{id}`
  - `/admin/blocks/service/{id}`
  - `/admin/blocks/city/{id}`
  - `/admin/blocks/static_page/{id}`
  - `/admin/blocks/blog_post/{id}`
  - `/admin/blocks/portfolio_project/{id}`
- Make a trivial unified change (toggle a block on/off) and save.
- Confirm save succeeds.

Legacy authoring endpoints must fail:

- Any UI route that previously edited “content blocks” must not allow saves.
- Any import/export pathway that attempts to write `page_content_blocks` must fail.

## 3) Confirm legacy read drift is visible but not catastrophic

Expected behavior:

- Legacy reads emit warning telemetry (log warning) so they are discoverable.
- Strict mode throws only when legacy reads occur for page types declared strictly unified.

Steps:

- View these public routes:
  - `/`
  - `/services`
  - `/locations`
  - `/blog`
  - `/portfolio`
  - `/contact`
  - `/faqs`
- Confirm no 500 errors for “strict unified” page types.
- Confirm legacy warnings appear only where expected (hybrid pages or transitional content).

## 4) Confirm the architecture freeze is intact

- Ensure `config/content_blocks.php` did not gain new legacy-only block types.
- Ensure newly introduced blocks are added only to `config/blocks.php`.

## 5) Run parity + cleanup tools (Phase 1 migration support)

On staging (SSH / deploy job):

- Report-only:
  - `php artisan blocks:sync-legacy`
  - `php artisan blocks:prune-legacy`
- If the report indicates missing unified parity, run:
  - `php artisan blocks:sync-legacy --write`
- After parity is complete, optionally prune:
  - `php artisan blocks:prune-legacy --write`

## 6) Verify production readiness gate

- Run:
  - `php artisan app:readiness-check --target=staging`
- Expected:
  - Fails if any legacy pages still have missing unified `page_blocks` parity.
  - Passes once parity is complete and configuration is correct.

## 7) Smoke audit (recommended)

- Run:
  - `php artisan app:smoke-audit --limit=3 --admin`
- Expected:
  - No failed checks.

