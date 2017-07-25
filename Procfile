web: vendor/bin/heroku-php-apache2 /public/

"scripts": {
  "heroku-postbuild": "npm run build && php artisan migrate --force && php artisan cache:clear"
}

