#!/bin/sh
set -e

echo "Starting Laravel container..."

if [ ! -f /var/www/html/artisan ]; then
    echo "Initializing application volume..."

    cp -R /opt/application/. /var/www/html
fi

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"