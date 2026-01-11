# Use official PHP CLI image
FROM php:8.4-cli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo_mysql

# Set working directory
WORKDIR /app

# Copy project files
COPY . /app

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install JWT library
RUN composer require firebase/php-jwt

# Expose port
EXPOSE 8080

# Start PHP built-in server with your router
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public", "./public/router.php"]
