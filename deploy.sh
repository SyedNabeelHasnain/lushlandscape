#!/bin/bash
set -euo pipefail

# Resolve directories relative to this script's location
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
LARAVEL_DIR="$SCRIPT_DIR/laravel"
PUBLIC_DIR="$SCRIPT_DIR/public_html"

# Colors
R='\033[0;31m' G='\033[0;32m' Y='\033[1;33m' B='\033[0;34m' NC='\033[0m'
log()     { echo -e "${B}[DEPLOY]${NC} $1"; }
ok()      { echo -e "${G}[  OK  ]${NC} $1"; }
warn()    { echo -e "${Y}[ WARN ]${NC} $1"; }
abort()   { echo -e "${R}[ABORT ]${NC} $1"; exit 1; }

DEPLOY_START=$(date +%s)

echo -e "${G}================================================${NC}"
echo -e "${G}   Lush Landscape — Automated Deployment${NC}"
echo -e "${G}   $(date '+%Y-%m-%d %H:%M:%S')${NC}"
echo -e "${G}================================================${NC}"
echo ""

# --- Configuration & Flags ---
FRESH_DB=false
ALLOW_NON_PRODUCTION=false
while [[ "$#" -gt 0 ]]; do
    case $1 in
        --fresh) FRESH_DB=true ;;
        --allow-non-production) ALLOW_NON_PRODUCTION=true ;;
        *) echo "Unknown parameter: $1"; exit 1 ;;
    esac
    shift
done

if [ "$FRESH_DB" = true ]; then
    warn "!!! FRESH MODE DETECTED — THIS WILL WIPE THE DATABASE !!!"
    sleep 3
fi

# --- Validate structure ---
log "Validating directory structure..."
[ -d "$LARAVEL_DIR" ] || abort "laravel/ directory not found at $LARAVEL_DIR"
[ -d "$PUBLIC_DIR" ]  || abort "public_html/ directory not found at $PUBLIC_DIR"
[ -f "$LARAVEL_DIR/.env" ] || abort ".env file missing in laravel/ — cannot proceed"
ok "Structure validated"

# --- Ensure all required directories ---
log "Ensuring required directories..."
mkdir -p "$LARAVEL_DIR/bootstrap/cache"
mkdir -p "$LARAVEL_DIR/storage/framework/sessions"
mkdir -p "$LARAVEL_DIR/storage/framework/views"
mkdir -p "$LARAVEL_DIR/storage/framework/cache/data"
mkdir -p "$LARAVEL_DIR/storage/logs"
mkdir -p "$PUBLIC_DIR/storage"
chmod 775 "$LARAVEL_DIR/bootstrap/cache"
rm -f "$LARAVEL_DIR/bootstrap/cache/"*.php || true
ok "Structure ready"

# --- Deployment guardrails ---
APP_ENV_VALUE=$(grep -E '^APP_ENV=' "$LARAVEL_DIR/.env" | head -1 | cut -d '=' -f2- | tr -d '"' || true)
APP_DEBUG_VALUE=$(grep -E '^APP_DEBUG=' "$LARAVEL_DIR/.env" | head -1 | cut -d '=' -f2- | tr -d '"' || true)
APP_URL_RAW=$(grep -E '^APP_URL=' "$LARAVEL_DIR/.env" | head -1 | cut -d '=' -f2- | tr -d '"' | tr -d '\r' || true)
APP_URL_VALUE=$(echo "${APP_URL_RAW:-}" | tr -d '\`' | tr -d ' ')
APP_HOST_VALUE="${APP_URL_VALUE#*://}"
APP_HOST_VALUE="${APP_HOST_VALUE%%/*}"
SCRIPT_DIR_BASENAME="$(basename "$SCRIPT_DIR" | tr '[:upper:]' '[:lower:]')"
PWD_BASENAME="$(basename "$PWD" | tr '[:upper:]' '[:lower:]')"
DEPLOY_TARGET_OVERRIDE="${DEPLOY_TARGET:-}"
READINESS_TARGET="production"

if [[ "${APP_URL_RAW:-}" == *\`* ]]; then
    warn "APP_URL contains backticks in laravel/.env. Continuing with sanitized value: ${APP_URL_VALUE}"
fi
if [ -z "${APP_URL_VALUE:-}" ]; then
    abort "APP_URL is missing or empty in laravel/.env"
fi

if [ -n "$DEPLOY_TARGET_OVERRIDE" ]; then
    case "$DEPLOY_TARGET_OVERRIDE" in
        production|staging) READINESS_TARGET="$DEPLOY_TARGET_OVERRIDE" ;;
        *) abort "DEPLOY_TARGET must be either 'production' or 'staging' (current: $DEPLOY_TARGET_OVERRIDE)." ;;
    esac
elif [[ "${APP_HOST_VALUE:-}" == *staging* ]] || [[ "${APP_HOST_VALUE:-}" == *test* ]] || [[ "$SCRIPT_DIR_BASENAME" == staging.* ]] || [[ "$PWD_BASENAME" == staging.* ]]; then
    READINESS_TARGET="staging"
fi

if [ "${ALLOW_NON_PRODUCTION}" != "true" ]; then
    [ "${APP_ENV_VALUE:-}" = "production" ] || abort "APP_ENV is '${APP_ENV_VALUE:-unset}'. Refusing deploy. Set APP_ENV=production or pass --allow-non-production intentionally."
    [ "${APP_DEBUG_VALUE:-}" != "true" ] || abort "APP_DEBUG=true detected. Refusing deploy. Disable debug mode or pass --allow-non-production intentionally."
else
    warn "Non-production deploy override enabled (--allow-non-production)."

    if [ "${APP_ENV_VALUE:-}" != "production" ]; then
        warn "APP_ENV is '${APP_ENV_VALUE:-unset}'."
    fi

    if [ "${APP_DEBUG_VALUE:-}" = "true" ]; then
        warn "APP_DEBUG=true detected."
    fi
fi

if [ "$READINESS_TARGET" = "staging" ] && [[ "${APP_HOST_VALUE:-}" != *staging* ]] && [[ "${APP_HOST_VALUE:-}" != *test* ]]; then
    abort "Staging deployment context detected, but APP_URL is '${APP_URL_VALUE:-unset}'. Set APP_URL=https://test.lushlandscape.ca in laravel/.env before deploying."
fi

if [ "$FRESH_DB" = true ] && [ "${ALLOW_NON_PRODUCTION}" != "true" ] && [ "$READINESS_TARGET" != "staging" ]; then
    abort "--fresh is only allowed on a staging host by default. Detected target is '${READINESS_TARGET}'. Set APP_URL to staging, deploy from a staging directory, or pass --allow-non-production intentionally."
fi

# --- Composer install ---
log "Installing Composer dependencies..."
cd "$LARAVEL_DIR"

# Detect if composer2 alias exists
COMPOSER_CMD="composer"
command -v composer2 &> /dev/null && COMPOSER_CMD="composer2"

if [ "$FRESH_DB" = true ]; then
    rm -rf vendor
    rm -f composer.lock
fi

$COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction
ok "Composer dependencies installed"

log "Preparing Node + NPM toolchain..."
if ! command -v npm &> /dev/null; then
    for BIN_DIR in /opt/alt/alt-nodejs*/root/usr/bin /opt/alt/alt-nodejs*/usr/bin; do
        if [ -x "${BIN_DIR}/node" ] && [ -x "${BIN_DIR}/npm" ]; then
            export PATH="${BIN_DIR}:$PATH"
            log "Using NodeJS from ${BIN_DIR}"
            break
        fi
    done
fi

if ! command -v npm &> /dev/null; then
    export NVM_DIR="$HOME/.nvm"
    if [ -s "$NVM_DIR/nvm.sh" ]; then
        \. "$NVM_DIR/nvm.sh"
    fi

    if ! command -v npm &> /dev/null; then
        if command -v curl &> /dev/null; then
            curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
        elif command -v wget &> /dev/null; then
            wget -qO- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
        else
            abort "npm is missing. Install NodeJS on this server (Hostinger NodeJS selector) or provide curl/wget so nvm can be installed."
        fi

        export NVM_DIR="$HOME/.nvm"
        [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    fi

    if command -v nvm &> /dev/null; then
        nvm install 20
        nvm use 20
    fi
fi

command -v npm &> /dev/null || abort "npm is still not available after attempting to install it."

log "Installing NPM dependencies (fresh)..."
rm -rf node_modules
if [ "$FRESH_DB" = true ]; then
    rm -f package-lock.json
fi
if [ -f package-lock.json ]; then
    npm ci --silent --no-audit --no-fund
else
    npm install --silent --no-audit --no-fund
fi
ok "NPM dependencies synced"

log "Removing previous frontend build artifacts..."
rm -rf "$PUBLIC_DIR/build"
rm -f "$PUBLIC_DIR/hot"
ok "Previous frontend build artifacts removed"

log "Building frontend assets (Vite)..."
npm run build

[ -f "$PUBLIC_DIR/build/manifest.json" ] || abort "Vite manifest missing at $PUBLIC_DIR/build/manifest.json after build."
ok "Vite build completed → public_html/build/"

# --- PHP Command Resolution ---
# Hostinger SSH defaults to an older PHP version. We explicitly use the PHP 8.4 binary.
PHP_CMD="php"
if [ -f "/opt/alt/php84/usr/bin/php" ]; then
    # We must explicitly pass the extension flags because the raw binary bypasses the hPanel CloudLinux wrappers
    PHP_CMD="/opt/alt/php84/usr/bin/php -d extension=nd_pdo_mysql.so -d extension=nd_mysqli.so"
    log "Using explicit PHP 8.4 binary with Native MySQL Drivers ($PHP_CMD)"
fi

# --- Clear application caches ---
log "Wiping application caches..."
$PHP_CMD artisan optimize:clear
ok "Cache cleared"

# --- Database Migration ---
if [ "$FRESH_DB" = true ]; then
    log "Wiping database and running fresh migrations + seeders..."
    # CAUTION: This deletes all existing data and runs the full suite of seeders.
    $PHP_CMD artisan migrate:fresh --seed --force
    ok "Fresh database installed and 100% seeded"
else
    log "Running standard database migrations..."
    $PHP_CMD artisan migrate --force
    ok "Database migrated safely"
fi

# --- Publish missing config files if they don't exist ---
log "Publishing config files..."
if [ ! -f "$LARAVEL_DIR/config/cors.php" ]; then
    $PHP_CMD artisan config:publish cors 2>/dev/null && ok "cors.php published" || warn "cors.php publish skipped"
else
    ok "cors.php already exists"
fi

if [ ! -f "$LARAVEL_DIR/config/auth.php" ]; then
    $PHP_CMD artisan config:publish auth 2>/dev/null && ok "auth.php published" || warn "auth.php publish skipped"
else
    ok "auth.php already exists"
fi

if [ ! -f "$LARAVEL_DIR/config/logging.php" ]; then
    $PHP_CMD artisan config:publish logging 2>/dev/null && ok "logging.php published" || warn "logging.php publish skipped"
else
    ok "logging.php already exists"
fi
ok "Config files ready"

# --- Storage directory (uploads write directly to public_html/storage/ — no symlink needed) ---
log "Ensuring public_html/storage/ directory exists..."
mkdir -p "$PUBLIC_DIR/storage"
ok "public_html/storage/ ready (uploads go here directly)"

# --- Generate sitemap ---
log "Generating sitemap..."
$PHP_CMD artisan sitemap:generate && ok "Sitemap generated" || warn "sitemap:generate failed — check logs"

# --- Production optimizations ---
log "Applying 100% fresh production optimizations..."
$PHP_CMD artisan config:cache
$PHP_CMD artisan route:cache
$PHP_CMD artisan view:cache
$PHP_CMD artisan event:cache
$PHP_CMD artisan optimize
ok "Config, route, view, and event caches strictly rebuilt"

# --- Production readiness gate ---
log "Running ${READINESS_TARGET} readiness checks..."
$PHP_CMD artisan app:readiness-check --target="$READINESS_TARGET"
ok "${READINESS_TARGET} readiness checks passed"

# --- Restart Queues ---
log "Restarting queue workers..."
$PHP_CMD artisan queue:restart 2>/dev/null || true
ok "Queues instructed to restart gracefully"

# --- Permissions ---
log "Setting file permissions..."
chmod -R 755 "$PUBLIC_DIR"
chmod -R 775 "$LARAVEL_DIR/storage"
chmod -R 775 "$LARAVEL_DIR/bootstrap/cache"
ok "Permissions set"

# --- Summary ---
DEPLOY_END=$(date +%s)
DEPLOY_DURATION=$((DEPLOY_END - DEPLOY_START))
APP_URL=$(grep -E '^APP_URL=' "$LARAVEL_DIR/.env" | head -1 | cut -d '=' -f2- | tr -d '"' || echo "https://lushlandscape.ca")

echo ""
echo -e "${G}================================================${NC}"
echo -e "${G}   DEPLOY COMPLETE (${DEPLOY_DURATION}s)${NC}"
echo -e "${G}================================================${NC}"
echo -e "  Frontend : ${APP_URL}"
echo -e "  Admin    : ${APP_URL}/admin/login"
echo -e "  Sitemap  : ${APP_URL}/sitemap.xml"
echo -e "  llms.txt : ${APP_URL}/llms.txt"
echo ""
