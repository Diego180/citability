FROM php:8.0-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY public/ /var/www/html/
COPY . /var/www/

RUN a2enmod rewrite

WORKDIR /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
