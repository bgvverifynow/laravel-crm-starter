FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    apache2 \
    libapache2-mod-php \
    libicu-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install intl bcmath gd pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader --no-interaction

RUN a2enmod rewrite

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]
