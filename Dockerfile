FROM php:7.4-fpm as base
RUN mkdir /usr/app && chown www-data /usr/app
WORKDIR /usr/app


FROM composer:2.0 as builder
WORKDIR /usr/app
ADD src src
ADD composer.json .
ADD composer.lock .
ADD symfony.lock .
RUN composer install --no-dev --optimize-autoloader --no-plugins --no-scripts


FROM base as prod
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
USER www-data
ADD bin bin
ADD config config
ADD public public
ADD src src
ADD composer.json .
ADD composer.lock .
ADD symfony.lock .
COPY --from=builder /usr/app/vendor /usr/app/vendor
RUN touch .env


FROM base as dev
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
USER www-data
