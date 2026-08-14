FROM wordpress:7.0.2-php8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        less \
        unzip \
    && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite

# Add optional local corporate CA certificates.
COPY certs/ /usr/local/share/ca-certificates/

RUN update-ca-certificates

COPY php/custom.ini /usr/local/etc/php/conf.d/nova-store.ini
