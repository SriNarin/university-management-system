# Use a modern, official PHP 8.4 Alpine Linux container base image
FROM php:8.4-fpm-alpine

# Install crucial system packages required by Laravel and Filament
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    bash

# Install and enable necessary PHP extension modules
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Pull down the latest stable edition of Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establish your working web path
WORKDIR /var/www/html
COPY . /var/www/html

# Run deployment setup variables and clear temporary configurations
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader

# Create the dedicated folder security structures for storage
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Inject the Nginx routing rule straight into the container environment
RUN echo 'server { \
    listen 80; \
    root /var/www/html/public; \
    index index.php; \
    charset utf-8; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        include fastcgi_params; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/http.d/default.conf

# Open web entry port 80
EXPOSE 80

# CRITICAL FIX: Run migrations and link storage automatically upon boot up, then start server
CMD php artisan migrate --force && php artisan storage:link && php artisan optimize:clear && php-fpm -D && nginx -g "daemon off;"