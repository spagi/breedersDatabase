FROM php:8.4-fpm-alpine

# System deps
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    sqlite \
    sqlite-dev \
    icu-dev \
    oniguruma-dev \
    nginx \
    supervisor

# PHP extensions
RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_sqlite \
    intl \
    mbstring \
    opcache

# Raise PHP memory limit (prevents OOM during composer/asset compile)
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
RUN mkdir -p var/cache var/log var/data public/uploads \
    && chmod -R 777 var public/uploads

# Build assets (AssetMapper)
RUN APP_ENV=prod php bin/console asset-map:compile --no-interaction

# Create schema (via migrations) and load demo fixtures.
# DoctrineFixturesBundle is dev-only, so we run in dev env and then copy
# the seeded database to the prod path so it is available at runtime.
RUN php bin/console doctrine:migrations:migrate --no-interaction \
    && php bin/console doctrine:fixtures:load --no-interaction \
    && cp var/data_dev.db var/data_prod.db

# Drop dev packages from the final image to keep it lean
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --no-progress

# Re-warm the prod cache after the autoloader has been trimmed
RUN APP_ENV=prod php bin/console cache:warmup --no-interaction

# Nginx + Supervisord configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf

# PHP-FPM tuning
RUN echo "pm.max_children = 10" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# Run as production so Symfony skips dev-only bundles (WebProfiler, etc.)
ENV APP_ENV=prod

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
