FROM php:8-fpm
RUN docker-php-ext-install mysqli
RUN apt-get update && apt-get install -y
RUN pecl install redis
RUN docker-php-ext-enable redis