#!/usr/bin/env bash

echo "=========================================="
echo "Starting Laravel Application"
echo "=========================================="

cd /var/www/html || exit 1

# Создаём .env из переменных окружения
echo "APP_NAME=\"Pokemon Stats Tracker\"" > .env
echo "APP_ENV=${APP_ENV:-production}" >> .env
echo "APP_DEBUG=${APP_DEBUG:-false}" >> .env
echo "APP_URL=${APP_URL}" >> .env
echo "APP_KEY=${APP_KEY}" >> .env
echo "DATABASE_URL=${DATABASE_URL}" >> .env
echo "POKEMON_API_URL=${POKEMON_API_URL:-https://pokeapi.co/api/v2/pokemon/}" >> .env
echo "POKEMON_MAX_ID=1025" >> .env

echo ".env file created. Contents:"
cat .env

# Принудительно генерируем ключ, если он не установлен или пуст
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "null" ]; then
    echo "APP_KEY is empty, generating..."
    php artisan key:generate --force
else
    echo "APP_KEY is already set, skipping generation."
fi

# Запускаем миграции
echo "Running migrations..."
php artisan migrate --force

# Запускаем PHP-FPM в фоне
echo "Starting PHP-FPM..."
php-fpm -D

# Запускаем Nginx в foreground (он не должен завершаться)
echo "Starting Nginx..."
nginx -g "daemon off;"

# Если Nginx по какой-то причине завершился, пишем в лог и останавливаем контейнер
echo "ERROR: Nginx exited unexpectedly."
exit 1