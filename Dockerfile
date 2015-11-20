FROM php:5.6-cli

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y \
    git \
    curl \
    && apt-get clean

RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

RUN echo "date.timezone = America/Montreal" >> /usr/local/etc/php/conf.d/symfony.ini

USER 1000

VOLUME /var/www/html
WORKDIR /var/www/html
