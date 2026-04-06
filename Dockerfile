# ================================
#  Laravel Universal Dockerfile
#  - Production ready
#  - Works for local development
#  - Multi-stage build
# ================================

# --------- Stage 1: Composer (Builder) ---------
FROM composer:2 AS builder

WORKDIR /app

# Copy entire Laravel project (except ignored via .dockerignore)
COPY . /app

# Install dependencies (optimized, no dev)
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# --------- Stage 2: Base PHP Image -------------
FROM php:8.3-fpm AS base

ARG DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/html

# System Dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev libpng-dev libjpeg-dev libwebp-dev \
    libfreetype6-dev libxml2-dev libonig-dev \
    default-mysql-client tzdata build-essential \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# GD + PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install -j$(nproc) pdo_mysql gd mbstring bcmath zip opcache

# Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# --------- Stage 3: Final Application Image ----
FROM base AS app

WORKDIR /var/www/html

# Copy project files
COPY --from=builder /app /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache || true


RUN printf "opcache.enable=%s\nopcache.validate_timestamps=%s\n" "${OPCACHE_ENABLE:-0}" "${OPCACHE_VALIDATE_TIMESTAMPS:-1}" \
  > /usr/local/etc/php/conf.d/opcache-dev.ini

# Expose PHP-FPM
EXPOSE 9000

COPY docker/entry.sh /usr/local/bin/entry.sh
RUN chmod +x /usr/local/bin/entry.sh
ENTRYPOINT ["/usr/local/bin/entry.sh"]

CMD ["php-fpm", "-R"]
