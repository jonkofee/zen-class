## Развернуть окружение
```bash
cp .env.dist .env
docker-compose up -d
```
### Установка зависимостей
```bash
docker exec -it zenclass_php_1 composer install
```
### Миграции
```bash
docker exec -it zenclass_php_1 vendor/bin/phinx migrate
```
### Сиды
```bash
docker exec -it zenclass_php_1 vendor/bin/phinx seed:run
```
