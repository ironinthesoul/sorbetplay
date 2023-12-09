FROM wordpress:6-php8.2-apache

COPY docker/ssl/localhost.crt /etc/apache2/ssl/localhost.crt
COPY docker/ssl/localhost.csr /etc/apache2/ssl/localhost.csr
COPY docker/ssl/localhost.ext /etc/apache2/ssl/localhost.ext
COPY docker/ssl/localhost.key /etc/apache2/ssl/localhost.key
COPY docker/ssl/RSAWEB-CA.key /etc/apache2/ssl/RSAWEB-CA.key
COPY docker/ssl/RSAWEB-CA.pem /etc/apache2/ssl/RSAWEB-CA.pem
COPY docker/ssl/RSAWEB-CA.srl /etc/apache2/ssl/RSAWEB-CA.srl

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY docker/apache2.conf /etc/apache2/apache2.conf
COPY docker/php.ini /usr/local/etc/php/php.ini

RUN apt-get update \
    && apt-get install -y unzip libxml2-dev wget \
    && a2enmod rewrite \
    && a2enmod ssl \
    && a2ensite default-ssl

RUN docker-php-ext-install soap

# COPY composer.json .

# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
#     && chmod +x /usr/bin/composer \
#     && composer install
