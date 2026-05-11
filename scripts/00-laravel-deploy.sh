#!/usr/bin/env bash

echo "Starting Laravel deployment..."

echo "Installing Composer dependencies..."
composer install --no-dev --working-dir=/var/www/html

echo "Generating application key..."
php artisan key:generate --force

echo "Clearing old cache..."
php artisan optimize:clear

echo "Caching configuration..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Caching views..."
php artisan view:cache

echo "Running migrations..."
php artisan migrate --force

echo "Deployment complete!"