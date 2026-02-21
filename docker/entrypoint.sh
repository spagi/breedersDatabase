#!/bin/sh
set -e

echo "[entrypoint] Waiting for MySQL..."
# depends_on healthcheck guarantees MySQL is up, but retry to be safe
RETRIES=30
until mysqladmin ping -h"${MYSQL_HOST:-db}" -u"${MYSQL_USER:-paw}" -p"${MYSQL_PASSWORD:-paw123}" --silent 2>/dev/null; do
    RETRIES=$((RETRIES - 1))
    if [ "$RETRIES" -eq 0 ]; then
        echo "[entrypoint] ERROR: MySQL not reachable after 60s. Exiting."
        exit 1
    fi
    echo "[entrypoint] MySQL not ready, retrying... ($RETRIES left)"
    sleep 2
done
echo "[entrypoint] MySQL is ready."

# Run pending migrations
echo "[entrypoint] Running database migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# Fix permissions after potential volume mount
chmod -R 777 var/ 2>/dev/null || true

# Warm the cache for the current environment
echo "[entrypoint] Warming cache (APP_ENV=${APP_ENV:-prod})..."
php bin/console cache:warmup --no-interaction 2>/dev/null || true

echo "[entrypoint] Starting nginx + php-fpm via supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
