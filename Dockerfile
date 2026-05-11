FROM php:8.2-fpm

# Установка Nginx и зависимостей
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip

# Копируем проект
COPY . /var/www/html
WORKDIR /var/www/html

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- ВАЖНО: СНАЧАЛА СОЗДАЁМ ПАПКИ И ДАЁМ ПРАВА ---
RUN mkdir -p /var/www/html/bootstrap/cache \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# --- ТЕПЕРЬ УСТАНАВЛИВАЕМ ЗАВИСИМОСТИ (ПРАВА УЖЕ ЕСТЬ) ---
RUN composer install --no-dev --optimize-autoloader

# Конфиг Nginx
RUN echo 'server { \
    listen 80; \
    server_name _; \
    root /var/www/html/public; \
    index index.php; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        include fastcgi_params; \
    } \
}' > /etc/nginx/sites-available/default

# Запуск
CMD php-fpm -D && nginx -g "daemon off;"