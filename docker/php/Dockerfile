FROM php:7.3-fpm-alpine

#Update
RUN apk update && apk upgrade

# Build and install PHP extensions
RUN docker-php-ext-install -j$(nproc) pdo && \
    docker-php-ext-install -j$(nproc) pdo_mysql