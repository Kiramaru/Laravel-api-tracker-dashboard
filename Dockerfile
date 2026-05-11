FROM richarvey/nginx-php-fpm:3.1.6

# Копируем весь проект в контейнер
COPY . .

# Устанавливаем переменные окружения
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Стандартные переменные Laravel
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# --- ПРАВИЛЬНЫЙ ПОРЯДОК ДЕЙСТВИЙ ---
# 1. Сначала создаем все необходимые папки и даем права
# 2. Затем запускаем Composer (теперь у него будут права на запись)

# Создаем папки для кэша и логов
RUN mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Устанавливаем PHP-зависимости 
RUN composer install --no-dev --optimize-autoloader

CMD ["/start.sh"]