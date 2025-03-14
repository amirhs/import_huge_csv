FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    cron \
    procps \
    netcat-traditional \
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
    && docker-php-ext-install gd pdo pdo_mysql xml \
    && rm -rf /var/lib/apt/lists/*

RUN echo "upload_max_filesize=500M\npost_max_size=500M\nmax_execution_time=300\nmemory_limit=2G" > /usr/local/etc/php/conf.d/custom.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data . /app

RUN composer install --no-interaction --no-progress --optimize-autoloader

RUN mkdir -p /app/var/uploads/csv && \
    chown -R www-data:www-data /app/var/uploads /app/var/log /app/var/cache

# Set up cron
COPY docker/cronjobs /etc/cron.d/symfony_cron
RUN chmod 0644 /etc/cron.d/symfony_cron

EXPOSE 8002

# No CMD here - we'll use the command in docker-compose.yml