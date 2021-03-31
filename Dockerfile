FROM composer:latest as build
WORKDIR /app
COPY . /app
RUN composer install --optimize-autoloader --no-dev

FROM php:8-apache as prod
RUN apt-get update && apt-get install openssl libssl-dev libcurl4-openssl-dev
RUN docker-php-ext-install pdo pdo_mysql mbstring ctype bcmath fileinfo json tokenizer xml

EXPOSE 443
EXPOSE 587
COPY --from=build /app /var/www/html/
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY .env.prod /var/www/html/.env
RUN chmod 777 -R /var/www/html/storage/ && echo "Listen 443" >> /etc/apache2/ports.conf && chown -R www-data:www-data /var/www/html/
RUN php artisan key:generate && php artisan config:cache && php artisan route:cache && php artisan view:cache
RUN a2enmod rewrite && a2enmod deflate && a2enmod headers && a2ensite default-ssl && a2enmod ssl

RUN apt-get update && apt-get install -y supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
CMD ["/usr/bin/supervisord"]

