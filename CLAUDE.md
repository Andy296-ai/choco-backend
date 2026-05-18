# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Backend для сети салонов красоты «Шоколад» — Laravel 12 / PHP 8.2. Обрабатывает бронирование, управление клиентами, расписание специалистов, аналитику и уведомления через Telegram.

## Commands

```bash
# Первый запуск
composer setup          # install + .env + key:generate + migrate + npm install + npm build

# Разработка (запускает сервер, очередь, логи и Vite одновременно)
composer dev

# Тесты
composer test           # config:clear + php artisan test
php artisan test --filter=TestClassName   # запуск одного теста

# Линтер (PHP CS Fixer)
./vendor/bin/pint

# Очистка кэша
php artisan cache:clear && php artisan config:clear && php artisan view:clear && php artisan route:clear

# Планировщик (задачи в routes/console.php)
php artisan schedule:run
```

## Architecture

### Двойная аутентификация

Два независимых Auth Guard:
- **`web`** — сотрудники (specialist, director, admin). Сессионная авторизация через пароль.
- **`client`** — клиенты. Сессионная авторизация + Telegram Widget OAuth (HMAC-SHA256 верификация с проверкой auth_date ≤ 24 ч).

Middleware `CheckRole` контролирует доступ по роли внутри guard'а `web`.

### Роли и панели управления

| Роль | Контроллер | Маршруты |
|------|-----------|---------|
| `admin` | `Panels/AdminApiController` | `/admin/*` |
| `director` | `Panels/DirectorController`, `DirectorDatabaseController` | `/director/*` |
| `specialist` | `Panels/SpecialistController` | `/specialist/*` |
| клиент | `ClientController`, `BookingController` | `/client/*`, `/booking` |

### Публичный API (`/api/*`)

- `GET /api/salons` — список салонов
- `GET /api/services` — список услуг
- `GET /api/specialists` — специалисты (фильтр по `salon_id`)
- `GET /api/available-slots` — свободные слоты (params: `specialist_id`, `date`, `salon_id`, `service_id`)

Логика доступности слотов в `BookingApiController` учитывает расписание специалиста (`Schedule`), отсутствия (`Absence`) и существующие записи (`Booking`).

### Ключевые модели и связи

```
Salon —< User (сотрудники) —< Schedule, Absence, PortfolioItem
Salon —< Client
Salon —< Booking >— Client, User (specialist), Service
```

### Уведомления

`TelegramNotificationService` отправляет сообщения клиентам через Telegram Bot API. Вызывается через Queue Job `SendTelegramNotification` (драйвер: database). Задачи планировщика в `routes/console.php` отправляют напоминания за 24 ч и 1 ч до записи.

### Frontend

TailwindCSS 4 + Vite 7. Blade-шаблоны в `resources/views/`. JavaScript (Axios) в `resources/js/`. Сборка через `npm run build` / `npm run dev`.

### Тесты

PHPUnit 11, база SQLite in-memory. Конфигурация в `phpunit.xml`. Тесты находятся в `tests/Unit/` и `tests/Feature/`.

### Ключевые файлы конфигурации

- `config/services.php` — Telegram Bot Token/Username
- `config/auth.php` — Guards `web` и `client`
- `routes/web.php` — все маршруты (~140+)
- `routes/console.php` — задачи планировщика
