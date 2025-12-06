FROM php:8.2-fpm

RUN apt update && apt install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    default-mysql-client \
    default-libmysqlclient-dev

RUN docker-php-ext-install pdo pdo_mysql
