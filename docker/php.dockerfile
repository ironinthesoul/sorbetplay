FROM php:8.0-fpm-alpine3.14

RUN apk update

RUN apk add \
    libzip-dev \
    nmap \
    vim \
    exiftool \
    imagemagick-dev \
    icu-dev \
    libmcrypt-dev \
    wkhtmltopdf \
    libxml2-dev \
    libpng-dev \
    php8-dev \
    build-base


RUN docker-php-ext-install zip exif mysqli bcmath gd intl soap


RUN pecl install imagick mcrypt
RUN docker-php-ext-enable imagick mcrypt soap


COPY . .

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# CMD /bin/sh -c "chown -R www-data:www-data /var/www/html && composer install"
CMD /bin/sh -c "chown -R www-data:www-data /var/www/html"
