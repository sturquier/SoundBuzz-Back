FROM php:7.2.9-apache

RUN mkdir /code
WORKDIR /code

COPY --from=composer:1.5 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader \
 && php bin/console cache:clear --env=prod --no-debug

ADD . /code/
COPY /code/ /var/www/html/

EXPOSE 8080