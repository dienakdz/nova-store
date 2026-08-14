FROM wordpress:7.0.2-php8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        less \
        unzip \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite

COPY php/custom.ini /usr/local/etc/php/conf.d/cham-coffee.ini