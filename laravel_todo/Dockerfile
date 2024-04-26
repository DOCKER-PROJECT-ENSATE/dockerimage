FROM php:8.2-fpm-alpine3.18
RUN docker-php-ext-install pdo pdo_mysql
RUN apk add libzip-dev
RUN docker-php-ext-install zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY ./laravel_todo .