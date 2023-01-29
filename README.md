# Install

Переименовать .env.example в .env и настроить подключение к БД
Выполнить команды:
	composer i
	npm i
	php artisan key:generate
	php artisan storage:link
	php artisan migrate

Для запуска среды разработки:
	php artisan serve
	npm run dev

Перейти на http://127.0.0.1:8000 и понажимать кнопки счетчика. Если работает, то все ок
