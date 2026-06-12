FROM richarvey/nginx-php-fpm:3.1.6

COPY . /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS=1

RUN composer install --no-dev

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80