#!/bin/bash

cd /app

php artisan key:generate
php artisan config:clear
composer dump-autoload
php artisan optimize:clear
php artisan serve --host=0.0.0.0 --port=8000
