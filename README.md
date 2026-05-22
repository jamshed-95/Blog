# Blog

Небольшой PHP-блог на `PHP 8.1+`, `Smarty` и `MySQL`.

## Быстрый запуск через Docker

1. Установите зависимости:

```bash
composer install
```

2. Запустите контейнеры:

```bash
docker compose up --build
```

3. Откройте приложение:

[`http://localhost:8080`](http://localhost:8080)

4. Заполните базу тестовыми данными:

[`http://localhost:8080/?r=seed`](http://localhost:8080/?r=seed)

## Локальный запуск через XAMPP

1. Установите зависимости:

```bash
composer install
```

2. Убедитесь, что включены `Apache` и `MySQL`.

3. Настройте подключение к БД через переменные окружения или значения в `config/config.php`:

- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `APP_BASE_URL`

4. Откройте проект в браузере по адресу вашей локальной конфигурации, например:

[`http://localhost/Blog/public`](http://localhost/Blog/public)

5. Выполните сидинг:

[`http://localhost/Blog/public/?r=seed`](http://localhost/Blog/public/?r=seed)

## Требования

- `PHP >= 8.1`
- `Composer`
- `MySQL 8+` или `Docker`
