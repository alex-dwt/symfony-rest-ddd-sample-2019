FROM php:7.2.11-fpm

RUN apt-get update && apt-get install -y vim \
    && docker-php-ext-install  pdo_mysql \
    && pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /app
CMD ["bash", "/app/bin/boot.bash"]

COPY php/www.conf /usr/local/etc/php-fpm.d/www.conf
# COPY ./app /app/

RUN docker-php-ext-install bcmath

COPY php/php-cli.ini /usr/local/etc/php