# Use official PHP 8.3.1 FPM image as the base
FROM php:8.3.1-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

# Install Xdebug for debugging
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure Xdebug for remote debugging
#COPY ./docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Copy custom PHP-FPM configuration file
#COPY my-php-fpm.conf /usr/local/etc/php-fpm.d/zz-myconf.conf

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM server
CMD ["php-fpm"]
