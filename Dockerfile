# SimpleSmallUtil image: installs PHP dependencies and serves the app with Apache.
FROM composer:2 AS vendor

ARG VERSION=1.0.20260920

WORKDIR /app

COPY composer.json ./
RUN composer install --no-interaction --prefer-dist --no-dev --no-progress --optimize-autoloader

FROM php:8.2-apache AS runtime

ARG VERSION=1.0.20260920

RUN a2enmod rewrite headers \
    && docker-php-ext-install pdo_mysql

COPY . /var/www/html
COPY --from=vendor /app/vendor /var/www/html/vendor

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/docker-php.conf \
    && chown -R www-data:www-data /var/www/html/runtime

LABEL org.opencontainers.image.version="${VERSION}"

EXPOSE 80
