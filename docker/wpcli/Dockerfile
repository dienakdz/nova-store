FROM wordpress:cli-2.11.0-php8.3

USER root

# Dùng cùng CA nội bộ với container WordPress để WP-CLI tải plugin qua HTTPS.
COPY wordpress/certs/ /usr/local/share/ca-certificates/
RUN update-ca-certificates

# Cho phép WP-CLI ghi rewrite rules chuẩn của Apache vào .htaccess.
COPY wpcli/wp-cli.yml /etc/wp-cli/config.yml

USER www-data
