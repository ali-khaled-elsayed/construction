# =============================================================================
# Laravel API — production multi-stage image (PHP 8.3-FPM)
# Build context: repository root (.)
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1: PHP 8.3-FPM + extensions (shared by vendor, production, worker)
# -----------------------------------------------------------------------------
FROM php:8.3-fpm-alpine AS base

LABEL maintainer="construction-marketplace"

# Build dependencies + headers for PECL / docker-php-ext-*
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    zlib-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev

# PHP 8.3 on Alpine requires --enable-gd (legacy --with-freetype flags fail)
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Runtime libraries required by gd.so / intl at PHP-FPM run time
RUN apk add --no-cache \
    freetype \
    libpng \
    libjpeg-turbo \
    zlib \
    libzip \
    icu-libs \
    oniguruma

COPY docker/backend/php.ini /usr/local/etc/php/conf.d/99-laravel.ini
COPY docker/backend/opcache.prod.ini /usr/local/etc/php/conf.d/99-opcache.ini

WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# Stage 2: Composer install (uses same PHP image so ext-intl/gd are available)
# -----------------------------------------------------------------------------
FROM base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_NO_INTERACTION=1

COPY construction-marketplace-api/ .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-ansi

# -----------------------------------------------------------------------------
# Stage 3: Production image (PHP-FPM)
# -----------------------------------------------------------------------------
FROM base AS production

COPY --from=vendor /var/www/html /var/www/html

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

COPY --from=vendor /var/www/html /var/www/html

RUN apk add --no-cache supervisor \
    && mkdir -p /etc/supervisor/conf.d \
        storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY docker/backend/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
