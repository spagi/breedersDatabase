FROM php:8.4-fpm-alpine

# System deps — MySQL client replaces SQLite
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    nginx \
    supervisor \
    mysql-client

# PHP extensions — pdo_mysql instead of pdo_sqlite
RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_mysql \
    intl \
    mbstring \
    opcache

# Raise PHP memory limit
RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/memory.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1

WORKDIR /var/www/html

# Copy composer manifests first — separate layer for better cache reuse
COPY composer.json composer.lock symfony.lock ./

# Install all deps (including dev) so fixtures bundle is available
RUN composer install --optimize-autoloader --no-interaction --no-scripts --no-progress

# Copy the rest of the application
COPY . .

# Run Composer post-install scripts now that full source is present
RUN composer run-script post-install-cmd --no-interaction 2>/dev/null || true

# Set up runtime directories
RUN mkdir -p var/cache var/log public/uploads \
    && chmod -R 777 var public/uploads

# Nginx + Supervisord configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# Entrypoint — runs migrations and starts supervisor at container launch
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# PHP-FPM tuning
RUN echo "pm.max_children = 10" >> /usr/local/etc/php-fpm.d/zz-docker.conf

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
