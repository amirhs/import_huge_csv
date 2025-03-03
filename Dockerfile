FROM composer:latest AS build

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./

RUN composer install --no-interaction --no-progress --optimize-autoloader --no-dev --no-scripts

FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libxml2-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql xml

COPY --chown=www-data:www-data . /app

COPY --from=build /app/vendor /app/vendor

RUN mkdir -p /app/var/uploads/csv && \
    chown -R www-data:www-data /app/var/uploads /app/var/log /app/var/cache

USER www-data

EXPOSE 8002

CMD ["php", "-S", "0.0.0.0:8002", "-t", "/app/public"]