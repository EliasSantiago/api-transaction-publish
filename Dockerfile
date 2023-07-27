FROM php:8.1-fpm

# Set your user name, ex: user=bernardo
ARG user=igt
ARG uid=1000

# Create the user and set the working directory
RUN useradd -G www-data,root -u $uid -d /home/$user $user
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev     # Adiciona a biblioteca do PostgreSQL

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd sockets   # Substitui pdo_mysql por pdo_pgsql

# Install redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# Install AMQP extension for RabbitMQ
RUN apt-get update && apt-get install -y librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy custom configurations PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Install cron service
RUN apt-get update && apt-get install -y cron

# Expose port 9000 (PHP-FPM)
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

# Set the user
USER $user