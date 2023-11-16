#!/usr/bin/env bash
set -e

app_env=${APP_ENV:-local}

if [ ! -d /.composer ]; then
    mkdir /.composer
fi

chmod -R ugo+rw /.composer

npm install
composer update --no-interaction --prefer-dist --optimize-autoloader --no-dev

php artisan migrate --force
php artisan db:seed
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan auth:clear-resets
php artisan app:sync

if [ "$app_env" == 'production' ]; then
    php artisan route:cache
    php artisan config:cache
    php artisan view:cache
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

while [ true ]
do
    php /var/www/html/artisan schedule:run --verbose --no-interaction &
    sleep 60
done
