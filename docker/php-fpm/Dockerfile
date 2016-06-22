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
 
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('SHA384', 'composer-setup.php') === 'bf16ac69bd8b807bc6e4499b28968ee87456e29a3894767b60c2d4dafa3d10d045ffef2aeb2e78827fa5f024fbe93ca2') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

USER callofbeer
 
WORKDIR /app