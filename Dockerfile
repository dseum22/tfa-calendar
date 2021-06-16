FROM composer:latest as build
WORKDIR /app
COPY . /app
RUN composer install --optimize-autoloader --no-dev

FROM php:8-apache as prod
RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 443
EXPOSE 587
COPY --from=build /app /var/www/html/
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini "$PHP_INI_DIR/php.ini"

RUN chmod 777 -R /var/www/html/storage/
RUN echo "Listen 443" >> /etc/apache2/ports.conf
RUN chown -R www-data:www-data /var/www/html/
RUN php artisan key:generate && php artisan config:cache && php artisan route:cache && php artisan view:cache
RUN a2enmod rewrite && a2enmod deflate && a2enmod headers

RUN apt-get update && apt-get install -y supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
CMD ["/usr/bin/supervisord"]

