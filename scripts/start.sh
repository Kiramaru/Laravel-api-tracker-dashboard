
#!/usr/bin/env bash

echo "=========================================="
echo "Starting Laravel Application"
echo "=========================================="

# Включаем отображение ошибок
echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini
echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini
echo "log_errors = On" >> /usr/local/etc/php/conf.d/errors.ini

cd /var/www/html || exit 1

# Права на SQLite (дублируем на случай, если папка пересоздалась)
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database
chmod -R 775 /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

# === ПРАВА НА СЕССИИ (ВАЖНО ДЛЯ 419) ===
mkdir -p /var/www/html/storage/framework/sessions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "=== SESSION DIAGNOSTIC ==="
ls -la /var/www/html/storage/framework/
ls -la /var/www/html/storage/framework/sessions/ || echo "Sessions folder missing"
touch /var/www/html/storage/framework/sessions/test || echo "Cannot write to sessions"
rm -f /var/www/html/storage/framework/sessions/test
php artisan session:table || echo "Session table check"
echo "=== END DIAGNOSTIC ==="


# Создаём .env
echo "APP_NAME=\"Pokemon Stats Tracker\"" > .env
echo "APP_ENV=${APP_ENV:-production}" >> .env
echo "APP_DEBUG=${APP_DEBUG:-false}" >> .env
echo "APP_URL=${APP_URL}" >> .env
echo "APP_KEY=${APP_KEY}" >> .env
echo "DATABASE_URL=${DATABASE_URL}" >> .env
echo "SESSION_DRIVER=array" >> .env
echo "POKEMON_API_URL=${POKEMON_API_URL:-https://pokeapi.co/api/v2/pokemon/}" >> .env
echo "POKEMON_MAX_ID=1025" >> .env

# Генерация ключа
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "null" ]; then
    php artisan key:generate --force
fi

# Миграции
php artisan migrate --force

# Создаём тестового пользователя, если таблица users пуста
echo "Checking for existing users..."
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" -eq 0 ]; then
    echo "No users found. Creating test user..."
    php artisan tinker --execute="App\Models\User::create(['name' => 'kiramaru', 'email' => 'kiramaru@example.com', 'password' => bcrypt('123')]);"
    echo "Test user created"
else
    echo "Users already exist. Skipping user creation."
fi

# Создание таблицы сессий
php artisan session:table
php artisan migrate --force

# Очистка кэша конфигов
php artisan config:clear
php artisan config:cache

# Удаляем старые сессии
rm -rf /var/www/html/storage/framework/sessions/*
chmod -R 777 /var/www/html/storage/framework/sessions

php artisan tinker --execute="echo 'Session test';"

# Запуск команды для добавления покемонов (несколько раз)
echo "=== Adding initial pokemons ==="

# Запускаем 5 раз с паузой чтобы не перегружать API
for i in 1 2 3 4 5
do
    echo "Fetching pokemon #$i..."
    php artisan pokemon:fetch
    sleep 2  # Пауза 2 секунды между запросами
done

echo "=== Initial pokemons added ==="

# Запуск серверов
echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx..."
nginx -g "daemon off;"

exit 1
