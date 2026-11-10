# syntax=docker/dockerfile:1

# ---------------------------------------------------------------------------
# Secret Republic - PHP 8.5 + Apache image
# ---------------------------------------------------------------------------
FROM php:8.5-apache-trixie

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public_html \
    FUEL_ENV=production \
    COMPOSER_ALLOW_SUPERUSER=1

# System packages, PHP extensions and Apache modules
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && docker-php-ext-install -j"$(nproc)" mysqli pdo_mysql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# Production php.ini plus a few app-specific overrides
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini "$PHP_INI_DIR/conf.d/zz-secretrepublic.ini"

# Serve public_html and honour its .htaccess
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf \
    && printf 'ServerTokens Prod\nServerSignature Off\n' > /etc/apache2/conf-available/zz-hardening.conf \
    && a2enconf zz-hardening

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install dependencies first so this layer is cached between code changes
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-dev --no-interaction --no-progress --no-scripts --no-autoloader

# Application code
COPY . .
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative \
    && mkdir -p fuel/app/cache fuel/app/logs fuel/app/tmp \
    && chown -R www-data:www-data fuel/app/cache fuel/app/logs fuel/app/tmp fuel/app/config \
    && rm /usr/bin/composer

COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/secretrepublic-entrypoint

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --start-interval=2s --retries=3 \
    CMD php -r 'exit(@file_get_contents("http://127.0.0.1/") === false ? 1 : 0);'

ENTRYPOINT ["secretrepublic-entrypoint"]
CMD ["apache2-foreground"]
