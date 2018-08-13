FROM php:7.1-fpm-alpine

RUN apk update && apk add \
  build-base \
  libpng-dev \
  curl-dev
  
RUN docker-php-ext-install \
  curl \
  gd \
  zip \
  pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

WORKDIR /var/www/html

EXPOSE 9000