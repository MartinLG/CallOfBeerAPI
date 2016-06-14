FROM php:5-fpm

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        php5-mysql \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

COPY php.ini /usr/local/etc/php/php.ini

COPY php-fpm.conf /usr/local/etc/php-fpm.conf

RUN useradd -ms /bin/bash callofbeer
 
USER callofbeer
 
WORKDIR /app