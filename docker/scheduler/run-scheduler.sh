#!/bin/sh
# =============================================================================
# Laravel scheduler — runs schedule:run every 60 seconds
# Alternative to system cron inside a dedicated container
# =============================================================================

set -e
cd /var/www/html

echo "Starting Laravel scheduler loop..."

while true; do
    php artisan schedule:run --verbose --no-interaction
    sleep 60
done
