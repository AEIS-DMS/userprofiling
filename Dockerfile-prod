FROM php:8.1-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    supervisor \
    libxml2-dev \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install -j$(nproc) \
    ctype \
    curl \
    dom \
    fileinfo \
    filter \
    session \
    gd \
    pdo_mysql \
    zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.5.8


RUN chmod +x /usr/local/bin/composer

RUN apt-get update && apt-get install -y nginx

COPY . .

COPY /docker/default.conf /etc/nginx/sites-available/default

COPY /docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN composer install

RUN chown -R www-data:www-data vendor/ && chown -R www-data:www-data storage/

EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]