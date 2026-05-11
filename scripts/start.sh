
#!/usr/bin/env bash

echo "=========================================="
echo "Starting Laravel Application"
echo "=========================================="

cd /var/www/html || exit 1

# Создаём .env
echo "APP_NAME=\"Pokemon Stats Tracker\"" > .env
echo "APP_ENV=${APP_ENV:-production}" >> .env
echo "APP_DEBUG=${APP_DEBUG:-false}" >> .env
echo "APP_URL=${APP_URL}" >> .env
echo "APP_KEY=${APP_KEY}" >> .env
echo "DATABASE_URL=${DATABASE_URL}" >> .env
echo "POKEMON_API_URL=${POKEMON_API_URL:-https://pokeapi.co/api/v2/pokemon/}" >> .env
echo "POKEMON_MAX_ID=1025" >> .env

echo ".env file created."

# Генерация ключа (если не установлен)
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "null" ]; then
    echo "APP_KEY is empty, generating..."
    php artisan key:generate --force
fi

# Запускаем все миграции (включая sessions)
echo "Running migrations..."
php artisan migrate --force

# Принудительно создаём таблицу sessions, если вдруг её нет
php artisan session:table
php artisan migrate --force

# Очищаем кэш конфигурации (важно!)
php artisan config:clear
php artisan cache:clear

# Запуск PHP-FPM и Nginx
echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
nginx -g "daemon off;"

exit 1