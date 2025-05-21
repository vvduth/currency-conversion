FROM php:8.3-fpm

WORKDIR /var/www/html

RUN  apt-get update && apt-get install -y \
    sqlite3 libsqlite3-dev zip unzip curl libzip-dev libicu-dev \
    && docker-php-ext-install pdo pdo_sqlite zip

# install intl
RUN docker-php-ext-install intl
