FROM richarvey/nginx-php-fpm:2.2.0

COPY . /var/www/html

WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache && php artisan route:cache

ENV WEBROOT /var/www/html/public
