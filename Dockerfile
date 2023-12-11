FROM yiisoftware/yii2-php:8.2-apache

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    libssl-dev \
    libfreetype6-dev \
    libjpeg-dev

RUN docker-php-ext-install curl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

COPY . /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-interaction --no-dev --prefer-dist

RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

RUN chown -R www:www /app

USER www