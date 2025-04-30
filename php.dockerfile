FROM php:8.4-fpm

COPY ./config/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./config/php.ini /usr/local/etc/php/conf.d/php.ini

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
