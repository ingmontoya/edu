# =============================================================================
# Stage 1: Frontend Builder
# Builds the Nuxt 3 static output using pnpm
# =============================================================================
FROM node:20-alpine AS frontend-builder

# Enable pnpm via corepack (matches packageManager: pnpm@10.26.1)
RUN corepack enable && corepack prepare pnpm@10.26.1 --activate

WORKDIR /app

# Copy frontend source (frontend/ subdirectory contents)
COPY frontend/package.json frontend/pnpm-lock.yaml ./

# Install dependencies with frozen lockfile for reproducible builds
RUN pnpm install --frozen-lockfile

# Copy remaining frontend source files
COPY frontend/ .

# Build static output — generates .output/public/
ARG NUXT_PUBLIC_API_URL=https://app.aula360.co/api
ENV NUXT_PUBLIC_API_URL=${NUXT_PUBLIC_API_URL}

RUN pnpm run generate

# =============================================================================
# Stage 2: Application
# PHP 8.2-fpm-alpine + nginx + supervisor serving the full app
# =============================================================================
FROM php:8.2-fpm-alpine AS app

# Install system dependencies
# - nginx: web server
# - supervisor: process manager for php-fpm, nginx, queue, scheduler
# - postgresql-dev: pdo_pgsql driver
# - libpng-dev, libjpeg-turbo-dev, freetype-dev: GD for dompdf PDF generation
# - libzip-dev: zip extension
# - oniguruma-dev: mbstring extension
# - fontconfig, ttf-freefont: font rendering for dompdf
RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    fontconfig \
    ttf-freefont \
    && rm -rf /var/cache/apk/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        mbstring \
        opcache \
        bcmath \
        pcntl \
        exif

# Copy Composer from official image (avoids installing it manually)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies first (layer-cached separately from app code)
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --optimize-autoloader \
        --no-scripts \
        --no-interaction \
        --prefer-dist

# Copy application source (everything except frontend/ — handled by .dockerignore)
COPY . .

# Copy Nuxt static build from Stage 1 into Laravel's public directory.
# This makes the Nuxt SPA the root of the web server — /api/** is handled
# by nginx routing to PHP-FPM, everything else serves the static SPA.
COPY --from=frontend-builder /app/.output/public/ /var/www/html/public/

# Regenerate autoload after full app copy (runs post-autoload scripts)
RUN composer dump-autoload --optimize --no-scripts

# Set correct ownership and permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy runtime configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini

# Nginx runs as www-data; create required directories
RUN mkdir -p /var/log/nginx /var/log/supervisor /run/nginx \
    && chown -R www-data:www-data /var/log/nginx /run/nginx

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
