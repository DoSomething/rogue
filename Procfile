web: vendor/bin/heroku-php-apache2 /public/

release: php artisan migrate --force
queue: php artisan queue:work --daemon --sleep=5
