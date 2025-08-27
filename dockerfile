FROM nginx:alpine

# Copy your Laravel Nginx config into conf.d
COPY nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html
