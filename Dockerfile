FROM composer as backend
WORKDIR /app
ADD composer.lock .
ADD composer.json .
ADD database database
RUN composer install --no-dev --no-scripts
ADD .. .
RUN composer dump-autoload


FROM alpine
WORKDIR /app/build
ADD .. .
COPY --from=backend /app/vendor ./vendor
