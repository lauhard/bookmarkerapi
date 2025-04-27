# ────── Build Stage ──────
FROM php:8.3-fpm-alpine AS builder

# Temporäre Build-Tools installieren
RUN apk add --no-cache --virtual .build-deps \
    libzip-dev \
    postgresql-dev \
    unzip \
    git \
    bash \
    && docker-php-ext-install pdo pdo_pgsql

# Composer installieren
COPY --from=composer:2.8.8 /usr/bin/composer /usr/bin/composer

# Arbeitsverzeichnis
WORKDIR /app

# Nur Composer-Dateien zuerst (für Caching)
COPY composer.json composer.lock ./

# Production-Abhängigkeiten installieren
RUN composer install --no-dev --optimize-autoloader

# App-Code kopieren
COPY . .

# ────── Runtime Stage ──────
FROM php:8.3-fpm-alpine AS runner

# Nur Runtime-Dependencies
RUN apk add --no-cache \
    libzip \
    libpq

# PHP mit Extensions aus builder übernehmen
COPY --from=builder /usr/local/lib/php /usr/local/lib/php
COPY --from=builder /usr/local/etc/php /usr/local/etc/php
COPY --from=builder /usr/local/bin/php* /usr/local/bin/

# App-Code übernehmen
WORKDIR /var/www/html
COPY --from=builder /app /var/www/html

# Composer im Runner entfernen (nicht nötig zur Laufzeit)
RUN rm -f /usr/bin/composer

# Rechte setzen
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
