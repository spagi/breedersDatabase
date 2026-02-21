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
    supervisor \
    nodejs \
    npm

# PHP extensions
RUN docker-php-ext-install \
    zip \
    pdo \
    pdo_sqlite \
    intl \
    mbstring \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install JS dependencies and build assets
RUN npm install 2>/dev/null || true

# Set up directories
RUN mkdir -p var/cache var/log var/data public/uploads \
    && chmod -R 777 var public/uploads

# Build assets
RUN php bin/console asset-map:compile --no-interaction

# Setup database
RUN php bin/console doctrine:schema:create --no-interaction \
    && php bin/console doctrine:fixtures:load --no-interaction

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Supervisord config
COPY docker/supervisord.conf /etc/supervisord.conf

# PHP-FPM config
RUN echo "pm.max_children = 10" >> /usr/local/etc/php-fpm.d/zz-docker.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
