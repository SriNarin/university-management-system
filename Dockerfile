FROM richarvey/nginx-php-fpm:latest

# Force the container to boot into PHP 8.4 environment layers
ENV PHP_VERSION=8.4
ENV DOCUMENT_ROOT=/var/www/html/public

# Optimize the environment for production execution speed
ENV APP_ENV=production
ENV ALIVE_TIMEOUT=180

COPY . /var/www/html

# Run the backend package optimizer
RUN composer install --no-dev --optimize-autoloader

# Compile your Filament v5 asset matrices and styling files
RUN npm install && npm run build

EXPOSE 80