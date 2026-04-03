FROM php:8.2-fpm

WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    libjpeg-dev libfreetype6-dev libicu-dev cron supervisor \
    libcurl4-openssl-dev pkg-config libssl-dev \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy Laravel app
COPY ./SupportTicketPortal /var/www

# Fix permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Install PHP dependencies as www-data
USER www-data
RUN composer install --no-dev --optimize-autoloader --no-scripts || composer diagnose

USER root

# Default command
CMD ["php-fpm"]


