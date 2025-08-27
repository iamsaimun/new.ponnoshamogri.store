# Stage 1: Base PHP + Composer
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip curl supervisor nginx \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Nginx & Supervisor configs
COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port
EXPOSE 80

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
