# Task Manager API (Laravel 10)

REST API на Laravel для управления задачами с приоритизацией на основе важности и дедлайна.

## Стек технологий

- Laravel 10+
- PHP 8.1+
- MySQL или PostgreSQL
- Laravel Request Validation
- Eloquent API Resources
- Swagger (L5-Swagger)

---

## Возможности API

- CRUD для задач (создание, просмотр, обновление, удаление)
- Фильтрация по статусу (`TODO`, `IN_PROGRESS`, `COMPLETED`)
- ⚖Приоритизированный список задач
- Покрытие тестами (PHPUnit)
- Swagger-документация

---

## Установка и запуск

```bash
# Клонировать репозиторий
git clone https://github.com/your-username/task-manager-api.git
cd task-manager-api

# Установить зависимости
composer install

# Создать .env
cp .env.example .env

# Сгенерировать ключ приложения
php artisan key:generate

# Настроить .env (БД)
DB_CONNECTION=mysql
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=your_password

# Запустить миграции
php artisan migrate

# Сгенерировать Swagger-документацию
php artisan l5-swagger:generate

# Тестирование
php artisan test

# Запустить сервер
php artisan serve

# API-документация Swagger UI
http://127.0.0.1:8000/api/documentation
