FROM php:7.4-apache

# Install extension php xdebug & pdo-pgsql
RUN apt-get update \
    && apt-get install -y libpq-dev zlib1g-dev libzip-dev \
    && pecl install xdebug \
    && docker-php-ext-install pgsql pdo_pgsql zip \
    && docker-php-ext-enable xdebug

# Tambah konfigurasi xdebug
RUN { \
    echo '[xdebug]' \
    echo 'xdebug.remote_enable=on'; \
    echo 'xdebug.remote_autostart=on'; \
    echo 'xdebug.remote_connect_back=on'; \
    echo 'xdebug.remote_port=9001'; \
    } > /usr/local/etc/php/conf.d/xdebug.ini

# Untuk production copy folder app
# COPY . /app

# Untuk development mount folder app
VOLUME [ "/app" ]

RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Aktifkan mod_rewrite
RUN a2enmod rewrite 

# Ganti document_root
ENV APACHE_DOCUMENT_ROOT /app/public

RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf

RUN sed -ri \
    -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf