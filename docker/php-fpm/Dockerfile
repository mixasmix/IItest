FROM --platform=linux/x86_64 php:8.2-fpm-alpine

ARG API_SOURCE_PATH='/var/www/api'
ARG APP_ENV='dev'

RUN apk update
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    vim \
    zip \
    zlib-dev \
    libzip-dev \
    postgresql-dev \
    icu-dev \
    git \
    bash \
    freetype \
    freetype-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    freetype-dev \
    libpng \
    libpng-dev \
    libwebp-dev \
    mc \
    libxml2 \
    libxml2-dev \
    openssl \
    tzdata

RUN docker-php-ext-install exif
RUN docker-php-ext-install zip
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo_pgsql

RUN rm -rf tmp/* /var/cache/apk/*
COPY www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR ${API_SOURCE_PATH}
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

CMD ["php-fpm"]
EXPOSE 9000

COPY entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
