# -- Base PHP-FPM image
FROM php:8.2-fpm-bullseye

# Set working directory
WORKDIR /var/www/html

# Install system deps (nginx, supervisor, libs, extensions)
RUN apt-get update && apt-get install -y \
    nginx supervisor git unzip curl libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer (copy from official image)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy app source
COPY . .

# Composer install (optimize for prod)
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction \
    && php artisan config:clear || true \
    && php artisan route:clear  || true \
    && php artisan view:clear   || true

# Ensure storage & cache permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && find storage -type d -exec chmod 775 {} \; \
    && find storage -type f -exec chmod 664 {} \; \
    && chmod -R ug+rwx bootstrap/cache

# Nginx config
COPY default.conf /etc/nginx/conf.d/default.conf

# Supervisor config to run php-fpm + nginx together
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Healthcheck (simple HTTP check against Nginx)
HEALTHCHECK --interval=30s --timeout=5s --retries=5 CMD curl -fsS http://localhost/ || exit 1

# Nginx runs on 80
EXPOSE 80

# Entrypoint: optional one-time Laravel tasks, then start Supervisor
# - APP_KEY generate if missing
# - storage:link (ignores if exists)
# - cache (ignores if failures)
RUN printf '%s\n' \
    '#!/usr/bin/env bash' \
    'set -e' \
    'if [ -f .env ]; then echo ".env found"; else cp .env.example .env || true; fi' \
    'if [ -z "$(grep -E "^APP_KEY=.+$" .env || true)" ]; then php artisan key:generate --force || true; fi' \
    'php artisan storage:link || true' \
    'php artisan config:cache || true' \
    'php artisan route:cache  || true' \
    'php artisan view:cache   || true' \
    'exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf' \
    > /entrypoint.sh && chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
