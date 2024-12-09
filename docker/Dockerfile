FROM php:8.1-fpm-alpine

RUN apk update && apk add --no-cache \
    libmemcached-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    autoconf \
    gcc \
    make \
    unzip \
    zlib-dev \
    build-base \
    && docker-php-ext-configure gd --with-freetype --with-jpeg="$(printf '%s' $(apk info -e libjpeg-turbo|head -n1))" \
    && docker-php-ext-install gd zip 

RUN pecl install igbinary && \
    docker-php-ext-enable igbinary && \
    pecl install msgpack && \
    docker-php-ext-enable msgpack

RUN pecl install redis && docker-php-ext-enable redis

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html/

COPY ./vendor ./vendor

COPY ./app .

VOLUME [ "/var/www/html/" ]

EXPOSE 80

CMD ["php-fpm"]