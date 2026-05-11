#!/usr/bin/env bash
echo "=========================================="
echo "Laravel Deployment Final Fix"
echo "=========================================="


mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/storage/logs

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


php artisan key:generate --force


php artisan migrate --force

echo "Deployment and setup complete!"