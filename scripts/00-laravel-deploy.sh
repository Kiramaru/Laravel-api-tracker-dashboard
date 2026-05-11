#!/usr/bin/env bash
echo "=========================================="
echo "Running migrations and final setup"
echo "=========================================="

# Генерируем ключ приложения (если он не установлен в переменных окружения)
php artisan key:generate --force

# Запускаем миграции
php artisan migrate --force

echo "Application is ready!"