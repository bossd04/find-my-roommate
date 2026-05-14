FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip \
    && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install

RUN php artisan key:generate || true
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
# Fix permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php -S 0.0.0.0:10000 -t public