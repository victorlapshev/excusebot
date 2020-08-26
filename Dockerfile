FROM php:7.4-fpm

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN apt update && apt install -y git
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN mkdir /usr/app && chown www-data /usr/app

USER www-data
WORKDIR /usr/app

ADD bin bin
ADD config config
ADD public public
ADD src src
ADD composer.json .
ADD composer.lock .
ADD symfony.lock .

RUN composer install --no-dev --optimize-autoloader --no-plugins --no-scripts

RUN touch .env