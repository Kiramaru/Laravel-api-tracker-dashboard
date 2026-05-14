# Laravel-api-tracker-dashboard

Веб-приложение для отслеживания статистики посещений и коллекционирования покемонов с интеграцией PokeAPI.

# О проекте

Приложение позволяет:
- Получать случайных покемонов из PokeAPI и сохранять их в базу данных
- Отслеживать посещения сайта с определением геолокации по IP
- Просматривать статистику посещений (почасовые графики, распределение по городам)
- Просматривать коллекцию пойманных покемонов с их характеристиками

Сайт: https://pokemon-stats-app-2q75.onrender.com

- # Тестовые данные для авторизации

- Почта: kiramaru@example.com
- Пароль: 123

- # Технологии

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Database**: PostgreSQL, также поддерживается MySQL/SQLite
- **Frontend**: Blade + TailwindCSS + Chart.js
- **APIs**: 
  - [PokeAPI](https://pokeapi.co/) - данные о покемонах
  - [ip-api.com](http://ip-api.com/) - геолокация по IP
- **Queue**: Database driver
- **Cache**: Database / File

- Планы по улучшению
    - Добавить WebSocket для обновления статистики в реальном времени
    - Запускать `pokemon:fetch` только при наличии активных пользователей на сайте каждые 5 минут

## Команды

Получить одного случайного покемона из API и сохранить в БД:
```bash
php artisan pokemon:fetch
```
## Быстрый старт

```bash
git clone https://github.com/Kiramaru/Laravel-api-tracker-dashboard.git
cd laravel-api-tracker-dashboard
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
    
