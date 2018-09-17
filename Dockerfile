FROM php:7.2.9-apache

RUN mkdir /code
WORKDIR /code

COPY --from=composer:1.5 /usr/bin/composer /usr/bin/composer

ADD . /code/
COPY /code/ /var/www/html/

RUN composer install --no-dev --optimize-autoloader \
 && php bin/console doctrine:database:create \
 && php bin/console doctrine:migrations:migrate \
 && php bin/console doctrine:fixtures:load \
 && php bin/console cache:clear --env=prod --no-debug

EXPOSE 8080

CMD php -S localhost:8080 -t /code/public/