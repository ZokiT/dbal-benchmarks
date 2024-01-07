FROM php:8.1.0-fpm

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN  apt-get update && apt-get install -y libpq-dev libicu-dev \
     && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
     && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql \
     && docker-php-ext-install intl

RUN apt-get install -y zip

COPY devbox-php.ini /usr/local/etc/php/conf.d/devbox-php.ini
