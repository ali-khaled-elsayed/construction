# =============================================================================
# Laravel API — production multi-stage image (PHP 8.3-FPM)
# Build context: repository root (.)
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1: Install Composer dependencies (no dev packages)
# -----------------------------------------------------------------------------
FROM composer:2 AS vendor

WORKDIR /app

COPY construction-marketplace-api/composer.json construction-marketplace-api/composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader

COPY construction-marketplace-api/ .

RUN composer dump-autoload --optimize --no-dev

# -----------------------------------------------------------------------------
# Stage 2: PHP runtime with extensions
# -----------------------------------------------------------------------------
FROM php:8.3-fpm-alpine AS base

LABEL maintainer="construction-marketplace"

RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip

RUN pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY docker/backend/php.ini /usr/local/etc/php/conf.d/99-laravel.ini
COPY docker/backend/opcache.prod.ini /usr/local/etc/php/conf.d/99-opcache.ini

WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# Stage 3: Production image
# -----------------------------------------------------------------------------
FROM base AS production

COPY --from=vendor /app /var/www/html

COPY docker/backend/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm", "-F"]

# -----------------------------------------------------------------------------
# Stage 4: Queue worker (Supervisor + same application code)
# -----------------------------------------------------------------------------
FROM base AS worker

COPY --from=vendor /app /var/www/html

RUN apk add --no-cache supervisor \
    && mkdir -p /etc/supervisor/conf.d storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY docker/backend/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
