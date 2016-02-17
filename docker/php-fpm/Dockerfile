FROM php:5.6-fpm

ADD symfony.ini /usr/local/etc/php/conf.d/

COPY docker-php-pecl-install /usr/local/bin/

RUN chmod +x /usr/local/bin/docker-php-pecl-install \
    && docker-php-ext-install pdo pdo_mysql mbstring \
    && docker-php-ext-enable opcache pdo_mysql mbstring \
    && docker-php-pecl-install xdebug-2.3.3

RUN usermod -u 1000 www-data