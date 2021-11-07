# Support Ticket

Сайт для техподдержки, построенный на базе фреймворка Laravel.

## Установка:

Клонировать репо:

```git clone git@github.com:Qweeeeex/test-ticket.git```

```https://github.com/Qweeeeex/test-ticket.git```

В консоли ввести:

```composer install```

## Настройка:
В файле .env.example заполните поля DB_***** и MAIL_***** на основе ваших данных и переименуйте его в .env

Войдите в каталог проекта, затем запустите:

```php artisan key:generate```

```php artisan migrate```

Если вы хотите заполнить базу данных тестовыми данными для входа в систему, введите в консоль:

```php artisan db:seed```

Данные для админа:
```email: admin@admin.com```
```pass: admin```

Данные для пользователя:
```email: user@user.com```
```pass: user```

Запуск проекта:

```php artisan serve```

Чтобы увидеть работу, перейдите по адресу http://localhost:8000/

