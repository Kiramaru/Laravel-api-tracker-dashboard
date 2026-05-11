FROM richarvey/nginx-php-fpm:3.1.6

# Копируем весь проект в контейнер
COPY . .

# --- КЛЮЧЕВЫЕ ПЕРЕМЕННЫЕ ДЛЯ ОБРАЗА ---
# Устанавливаем зависимости Composer во время сборки
ENV SKIP_COMPOSER 0
# Указываем папку, где лежит index.php
ENV WEBROOT /var/www/html/public
# Включаем вывод ошибок PHP
ENV PHP_ERRORS_STDERR 1
# Разрешаем выполнение скриптов при старте
ENV RUN_SCRIPTS 1
# Включаем заголовок для реального IP
ENV REAL_IP_HEADER 1

# Стандартные переменные Laravel (можно переопределить в Render)
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Разрешаем Composer работать от суперпользователя
ENV COMPOSER_ALLOW_SUPERUSER 1

# Образ сам выполнит composer install и запустит сервер
CMD ["/start.sh"]