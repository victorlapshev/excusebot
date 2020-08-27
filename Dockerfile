FROM php:7.4-fpm as base
RUN mkdir /usr/app && chown www-data /usr/app
RUN apt update && apt install -y libpq-dev
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql
WORKDIR /usr/app


FROM composer:2.0 as builder
WORKDIR /usr/app
ADD src src
ADD composer.json .
ADD composer.lock .
ADD symfony.lock .
RUN composer install --no-dev --optimize-autoloader --no-plugins --no-scripts


FROM base as prod
RUN mv "$PHP_INI_DIR/php.ini-production" "c"
RUN docker-php-ext-install opcache && docker-php-ext-enable opcache && docker-php-ext-configure opcache
USER www-data
ADD bin bin
ADD config config
ADD public public
ADD migrations migrations
ADD src src
ADD composer.json .
ADD composer.lock .
ADD symfony.lock .
COPY --from=builder /usr/app/vendor /usr/app/vendor
RUN touch .env


FROM base as dev
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN pecl install xdebug && docker-php-ext-enable xdebug
ENV PHP_IDE_CONFIG serverName=docker
ADD .docker/dev-php.ini "$PHP_INI_DIR/conf.d/dev.ini"
USER www-data
