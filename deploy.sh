#!/bin/bash

cd ~/domains/yuntaspublicidad.com/public_html/back || exit

echo ">> Reset repo"
git fetch --all
git reset --hard origin/main
git clean -df

echo ">> Install composer deps"
composer install --no-dev --optimize-autoloader

echo ">> corriendo migraciones"
php artisan migrate --force

echo ">> Clear caches"
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache

echo ">> Deploy finalizado"
