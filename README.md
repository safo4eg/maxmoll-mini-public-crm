# Инструкция по разворачиванию проекта

Для начала клонируем сам проект в любое место, в примере создается папка maxmoll

```
    git clone https://github.com/safo4eg/maxmoll-mini-public-crm.git maxmoll
```

***Все дальнейшие команды должны выполняться из корня с файлом docker-compose.yml***

## Первый запуск

Поднимаем контейнеры

```
    docker compose up -d --build
```

## Подключение к контейнеру приложения

Для того чтобы выполнить необходимые команды для работы приложения laravel, нужно подключиться
к контейнеру maxmoll_app через bash-оболочку

```
    docker exec -it maxmoll_app bash
```

## Подгружаем зависимости

С помощью композера загружаем зависимости

```
    composer install
```


## Настройка Laravel приложения

Все эти команды выполняются последовательно внутри контейнера maxmoll_app

```
    cp .env.example .env
```

```
    php artisan key:generate
```

```
    php artisan migrate --seed
```

## Необходимые ресурсы

1. [коллекция Postman](https://web.postman.co/workspace/97400a92-93e0-4cc5-8b4b-a57010cf5c13/documentation/35026712-aca21b06-6e1d-4e49-b099-c54bad838d05)