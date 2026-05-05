# ── Stage 1: build frontend assets ──────────────────────────────────────────
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ── Stage 2: production PHP app ───────────────────────────────────────────────
FROM php:8.2-fpm-alpine AS app

# System deps + PHP extensions
RUN apk add --no-cache \
      nginx \
      supervisor \
      sqlite \
      sqlite-dev \
      curl \
      unzip \
    && docker-php-ext-install pdo pdo_sqlite opcache pcntl \
    && apk del sqlite-dev

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHP dependencies (no dev)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# App source
COPY . .

# Frontend assets built in stage 1
COPY --from=assets /app/public/build ./public/build

# Config files
COPY docker/nginx.conf       /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh    /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Permisos de Laravel
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
