# Lush Landscape Service Deployment Guide

## Architecture

- Application code lives in `laravel/`
- Public web root lives in `public_html/`
- `public_html/index.php` boots the Laravel app from `../laravel`
- Public uploads are served directly from `public_html/storage`
- Built frontend assets are written to `public_html/build`
- Production dependencies should be installed from `composer.lock` and `package-lock.json`

## Before You Deploy

- Keep the split structure intact:
  - `.../laravel/app`
  - `.../laravel/bootstrap`
  - `.../laravel/config`
  - `.../laravel/database`
  - `.../laravel/resources`
  - `.../laravel/routes`
  - `.../laravel/storage`
  - `.../laravel/vendor`
  - `.../public_html/index.php`
  - `.../public_html/.htaccess`
  - `.../public_html/build`
  - `.../public_html/storage`
- Do not publish plaintext credentials in this file or in version control.
- Set `APP_ENV=production` and `APP_DEBUG=false` before go-live.
- Use a staging `APP_URL` such as `https://staging.lushlandscape.ca` when you want a production-grade staging deploy that can still run `./deploy.sh --fresh` safely.
- Ensure the target database, mail server, and cron access are available before switching traffic.

## Environment Setup

Create or update `laravel/.env` with real production values:

```dotenv
APP_NAME="Lush Landscape Service"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://lushlandscape.ca

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=465
MAIL_USERNAME=your-mailbox
MAIL_PASSWORD=your-mail-password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@your-domain.com
MAIL_FROM_NAME="Lush Landscape Service"

FILESYSTEM_DISK=public
SESSION_SECURE_COOKIE=true
ADMIN_SEED_PASSWORD=change-this-before-seeding
```

Generate an app key if needed:

```bash
cd laravel
php artisan key:generate
```

## Recommended Deployment Flow

From the project root:

```bash
./deploy.sh
```

The script will:

- validate the required directory layout
- install PHP dependencies from `laravel/composer.lock`
- install frontend dependencies from `laravel/package-lock.json`
- remove any previous `public_html/build` artifacts before rebuilding
- build assets into `public_html/build`
- run migrations
- generate the sitemap
- rebuild Laravel caches
- auto-run `php artisan app:readiness-check --target=staging|production` based on `APP_URL`
- also infers staging when the deployment directory name starts with `staging.`

For a brand-new staging database with seed data:

```bash
./deploy.sh --fresh
```

`--fresh` is intentionally blocked on non-staging hosts unless you also pass `--allow-non-production`.
If the server directory is clearly staging but `laravel/.env` still uses the live `APP_URL`, the script will stop and ask you to switch `APP_URL` to `https://staging.lushlandscape.ca` first.

## Manual Deployment Flow

If you need to deploy manually instead of using the script:

```bash
cd laravel
composer install --no-dev --optimize-autoloader --no-interaction
npm ci
npm run build
php artisan optimize:clear
php artisan migrate --force
php artisan sitemap:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
php artisan app:readiness-check --target=production
```

## First-Time Setup

- Seed only when you are preparing a fresh environment:

```bash
cd laravel
php artisan migrate --seed --force
```

- If you seed the admin user, set `ADMIN_SEED_PASSWORD` first.
- Change the seeded admin password immediately after first login.

## Storage

- The public disk is configured to use `public_html/storage`
- No `storage:link` step is required in this architecture
- Make sure `public_html/storage` exists and is writable

## Cron

Run the scheduler every minute:

```bash
/usr/bin/php /path/to/project/laravel/artisan schedule:run >> /dev/null 2>&1
```

Current scheduled task:

- `sitemap:generate` daily

## Post-Deploy Verification

- Frontend home page loads
- Admin login loads at `/admin/login`
- Database migrations succeed
- `sitemap.xml` loads
- `llms.txt` loads
- Form submissions work end to end
- Media uploads and downloads work
- CMS create/edit flows redirect correctly
- `APP_ENV=production`
- `APP_DEBUG=false`
- `php artisan app:readiness-check --target=production` passes for live
- `php artisan app:readiness-check --target=staging` passes for staging

## Safety Notes

- Never commit real `.env` secrets
- Never store plaintext admin credentials in documentation
- Prefer lockfile-based installs for repeatable deployments
- Do not delete the current `vendor`, `node_modules`, or built assets before replacement artifacts are ready
