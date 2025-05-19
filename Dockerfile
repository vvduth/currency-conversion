FROM php:8.3.21-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    bash \
    fcgiwrap \
    libmcrypt-dev \
    libonig-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# install php extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_sqlite pdo_pgsql zip opcache

# composer
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html


USER www-data

EXPOSE 8000

CMD ["php-fpm"]
