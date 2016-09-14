# Rogue

Rogue is Dossomething.org's campaign activity application.

This application is built using the [Laravel](https://laravel.com) Framework.

### Getting Started
Fork and clone this repository, and set it up as a local app running inside [DS Homestead](https://github.com/DoSomething/ds-homestead) or regular [Homestead](https://github.com/laravel/homestead).

Create a `.env` file from `.env.example` and make sure your `.env.local.php` has the correct keys for your dev environment. Reach out to the tech team for the correct keys to use in your dev environment.

Edit your `Homestead.yml` file to include this new project. Making sure the `folders` and `sites` configuration is correct for your local set up. You might need to run `vagrant provision` after you make this update.

Add your app url (rogue.dev) to your `etc/hosts` file i.e. `127.0.0.1 rogue.dev`

Manually create a `rogue` database in Sequel Pro.
  - Open a `new connection` window and click on the `standard` connection tab
  - Name the connection
  - Enter host info: `127.0.0.1`
  - Username: `homestead`
  - Enter password from `.env.local.php` file
  - Port: `33060`
  - Hit `connect`
  - In the `choose database` dropdown select `add database`
  - Name the database `rogue` with UTF-8 encoding.
  - In order to run tests, make another database called `rogue_test`

After the initial Homestead installation `ssh` into the vagrant box, head to the project directory and run composer to install all the project dependencies:

```shell
$ composer install
```

Once all vendor dependencies are installed, run the migrations & seeders to setup the database:

```shell
$ php artisan migrate
$ php artisan db:seed
```
You can build front-end assets (styles & scripts) with [Webpack](https://github.com/DoSomething/webpack-config):

```shell
$ npm install
$ npm run build
```

For development run `npm start` to watch files

# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
