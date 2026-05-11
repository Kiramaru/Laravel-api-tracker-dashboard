
#!/usr/bin/env bash

echo "=========================================="
echo "Starting Laravel Application"
echo "=========================================="

# Создаём .env из переменных окружения
echo "APP_NAME=\"Pokemon Stats Tracker\"" > /var/www/html/.env
echo "APP_ENV=production" >> /var/www/html/.env
echo "APP_DEBUG=false" >> /var/www/html/.env
echo "APP_URL=${APP_URL}" >> /var/www/html/.env
echo "" >> /var/www/html/.env
echo "DB_CONNECTION=pgsql" >> /var/www/html/.env
echo "DB_HOST=${DB_HOST}" >> /var/www/html/.env
echo "DB_PORT=${DB_PORT}" >> /var/www/html/.env
echo "DB_DATABASE=${DB_DATABASE}" >> /var/www/html/.env
echo "DB_USERNAME=${DB_USERNAME}" >> /var/www/html/.env
echo "DB_PASSWORD=${DB_PASSWORD}" >> /var/www/html/.env
echo "" >> /var/www/html/.env
echo "POKEMON_API_URL=${POKEMON_API_URL:-https://pokeapi.co/api/v2/pokemon/}" >> /var/www/html/.env
echo "POKEMON_MAX_ID=1025" >> /var/www/html/.env

# Генерация ключа
php artisan key:generate --force

# Миграции
php artisan migrate --force

# Запуск PHP-FPM и Nginx
php-fpm -D
nginx -g "daemon off;"