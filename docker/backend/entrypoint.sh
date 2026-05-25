#!/bin/sh
set -e

# =============================================================================
# Laravel backend entrypoint
# Waits for MySQL, links storage, optionally migrates/caches, then runs CMD.
# =============================================================================

cd /var/www/html

# -----------------------------------------------------------------------------
# Discover packages (skipped during image build with --no-scripts)
# -----------------------------------------------------------------------------
php artisan package:discover --ansi 2>/dev/null || true

# -----------------------------------------------------------------------------
# Wait for database (when DB_HOST is set)
# -----------------------------------------------------------------------------
if [ -n "${DB_HOST}" ] && [ "${DB_CONNECTION}" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."
    until php artisan db:show 2>/dev/null; do
        echo "MySQL not ready — retrying in 3s..."
        sleep 3
    done
    echo "MySQL is ready."
fi

# -----------------------------------------------------------------------------
# Storage symlink (public disk uploads)
# -----------------------------------------------------------------------------
if [ ! -L public/storage ]; then
    php artisan storage:link --force 2>/dev/null || true
fi

# -----------------------------------------------------------------------------
# Optional migrations (set RUN_MIGRATIONS=true in staging/prod deploy)
# -----------------------------------------------------------------------------
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

# -----------------------------------------------------------------------------
# Production optimizations (skip in local development)
# -----------------------------------------------------------------------------
if [ "${APP_ENV}" = "production" ] || [ "${APP_ENV}" = "staging" ]; then
    php artisan config:cache --no-interaction 2>/dev/null || true
    php artisan route:cache --no-interaction 2>/dev/null || true
    php artisan view:cache --no-interaction 2>/dev/null || true
fi

# -----------------------------------------------------------------------------
# Fix permissions for www-data
# -----------------------------------------------------------------------------
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

exec "$@"
