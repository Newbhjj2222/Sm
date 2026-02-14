FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev \
    && docker-php-ext-install pcntl zip

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction

# Copy all application code
COPY . .

# Default command
CMD ["php", "-a"]
