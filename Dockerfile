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
    librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql xml

RUN echo "upload_max_filesize=500M\npost_max_size=500M\nmax_execution_time=300\nmemory_limit=2G" > /usr/local/etc/php/conf.d/custom.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data . /app

RUN composer install --no-interaction --no-progress --optimize-autoloader

RUN mkdir -p /app/var/uploads/csv && \
    chown -R www-data:www-data /app/var/uploads /app/var/log /app/var/cache

USER www-data

EXPOSE 8002

CMD ["php", "-S", "0.0.0.0:8002", "-t", "/app/public"]
