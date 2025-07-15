FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    curl \
    libzip-dev \
    sqlite-dev \
    libpq-dev \
    jpeg-dev \
    libpng-dev \
    mysql-client \
    git \
    supervisor \
    nginx \
    oniguruma-dev \
    build-base \
    autoconf \
    linux-headers

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www/html

COPY . .

COPY composer.json composer.lock ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

COPY docker/php/php-fpm-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
