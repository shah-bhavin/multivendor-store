FROM php:8.3-fpm

# Install Packages

RUN apt-get update && apt-get install -y \

    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Install PHP Extensions

RUN docker-php-ext-install \

    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Install Composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set Working Directory

WORKDIR /var/www

# Copy Files

COPY . .

# Install Laravel Dependencies

RUN composer install

# Permissions

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]