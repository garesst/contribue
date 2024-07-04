FROM php:8.2-apache
LABEL authors="gares"
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql
COPY /contribue /var/www/html
EXPOSE 80
RUN a2enmod rewrite