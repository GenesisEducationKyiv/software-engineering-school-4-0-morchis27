FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    unzip \
    libonig-dev \
    libpq-dev \
    libzip-dev \
    supervisor

RUN docker-php-ext-install -j$(nproc) mbstring pgsql pdo_pgsql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini
RUN composer global require phpunit/phpunit

EXPOSE 9000
