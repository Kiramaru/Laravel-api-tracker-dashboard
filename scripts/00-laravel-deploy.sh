#!/usr/bin/env bash
echo "=========================================="
echo "Laravel Deployment Script (Render Fix)"
echo "=========================================="


echo "Setting correct permissions for Laravel..."


mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/storage/framework/{cache,sessions,testing,views}
mkdir -p /var/www/html/storage/logs


chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache


echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

echo "Generating application key..."
php artisan key:generate --force


echo "Clearing and rebuilding cache..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache


echo "Running migrations..."
php artisan migrate --force

echo "Deployment complete!"