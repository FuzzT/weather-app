FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    zip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install zip pdo mbstring

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy only composer files first to optimize cache
COPY composer.json composer.lock ./

# Run composer install
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader

# Copy the rest of the application
COPY . .

# Create required Laravel directories
RUN mkdir -p storage/logs bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 8000

# Start Laravel using built-in server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
