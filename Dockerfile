FROM php:8.3-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libpq-dev \
    libsqlite3-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Better Docker cache
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# Copy application
COPY . .

RUN composer dump-autoload \
    --optimize \
    --no-dev

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

RUN mkdir -p storage bootstrap/cache

RUN chown -R www-data:www-data storage bootstrap/cache

# Store a pristine copy of the application
RUN mkdir -p /opt/application \
    && cp -a /var/www/html/. /opt/application/

EXPOSE 9000

CMD ["php-fpm"]