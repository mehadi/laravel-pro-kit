# Use the official PHP image as the base image
FROM php:8.2.8-apache

# Set working directory within the container
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    zip \
    unzip

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Copy application files into the container
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Set ServerName directive to suppress the warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable Apache modules
RUN a2enmod rewrite

# Set ownership and permissions
RUN chown -R www-data:www-data storage
RUN chmod -R 775 storage
RUN chmod g+s storage

# Expose port 80 for Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
