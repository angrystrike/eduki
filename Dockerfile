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
    oniguruma-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

WORKDIR /var/www/html

COPY . .

COPY composer.json composer.lock ./

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
