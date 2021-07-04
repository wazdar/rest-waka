FROM php:7.4-fpm

RUN apt-get update \
    && apt-get install -y libpq-dev libzip-dev libxml2-dev zip libyaml-dev \
    && pecl install xdebug-2.8.1 yaml-2.2.0 \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip xml \
    && docker-php-ext-enable xdebug zip xml pgsql yaml \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && mkdir /.composer && chown -R ${DOCKER_USER}:${DOCKER_USER} /.composer

RUN chmod 755 /usr/local/bin/composer
RUN chown -R www-data:www-data /var/www

COPY ./app /var/www

WORKDIR /var/www
USER www-data

VOLUME ["/var/www"]
CMD bash -c "composer install && php -S 0.0.0.0:8080 -t public"