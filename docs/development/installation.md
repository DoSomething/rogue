# Installation

Clone this repository, and [add it to your Homestead](https://github.com/DoSomething/communal-docs/blob/master/Homestead/readme.md). Homestead provides a pre-packaged development environment to help get you up and running quickly!

```sh
# Install dependencies
$ composer install && npm install

# Configure application & run migrations:
$ php artisan rogue:setup

# And finally, build the frontend assets:
$ npm run build

# Also, feel free to seed the database with dummy data:
$ php artisan db:seed
```
