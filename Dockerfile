FROM richarvey/nginx-php-fpm:latest

# 1. Force the container to use PHP 8.4
ENV PHP_VERSION=8.4
ENV DOCUMENT_ROOT=/var/www/html/public

# 2. Add PHP 8.4 paths directly to the system environment execution loop
ENV PATH="/usr/bin/php8.4:/usr/sbin/php8.4:$PATH"
ENV APP_ENV=production

COPY . /var/www/html

# 3. CRITICAL FIX: Tell composer to ignore local platform restrictions during build
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 4. Compile frontend asset matrices
RUN npm install && npm run build

EXPOSE 80