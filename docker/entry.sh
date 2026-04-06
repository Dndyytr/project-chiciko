#!/bin/sh
set -e
cd /var/www/html

# Ensure dirs exist and owned by www-data (only try, ignore failures)
mkdir -p storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# run php-fpm
exec php-fpm
