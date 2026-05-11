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

# ЯВНАЯ УСТАНОВКА ЗАВИСИМОСТЕЙ
# Это ключевая часть, которая решит проблему
RUN composer install --no-dev --optimize-autoloader

# Создаем папки для кэша и прав
RUN mkdir -p /var/www/html/storage/framework/cache \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs \
    /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache

CMD ["/start.sh"]