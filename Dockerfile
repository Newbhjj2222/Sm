# Base image
FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install dependencies
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction

# Copy all application code
COPY . .

# Default command
CMD ["php", "-a"]
