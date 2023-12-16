# Set the base image for PHP and Apache
FROM php:8.2-apache

# Maintainer Information
LABEL maintainer="Nikolay Razlozhki <nikrazbg@gmail.com>"

# Install system dependencies for PHP extensions
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && a2enmod rewrite

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the application source code
COPY . /var/www/html

# Set the working directory to the Apache web root
WORKDIR /var/www/html

# Install PHP dependencies with Composer
RUN composer install --optimize-autoloader

# Install Node.js for front-end asset management
RUN apt-get update && apt-get install -y nodejs npm

# Install Node dependencies
RUN npm install

# Set permissions for the Laravel application
RUN chown -R www-data:www-data /var/www/html

COPY ./docker/apache/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

# Expose port 80 to access the Apache server
EXPOSE 80

# Command to start the Apache server in the foreground
CMD ["apache2-foreground"]
