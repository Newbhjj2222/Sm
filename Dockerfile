FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y zip unzip git curl libzip-dev \
    && docker-php-ext-install pcntl zip

WORKDIR /app

COPY composer.json composer.lock ./

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction

COPY . .

# Expose HTTP port for container app
EXPOSE 8080

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/app"]
