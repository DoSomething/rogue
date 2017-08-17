# Rogue [![wercker status](https://app.wercker.com/status/518aafc1808a35e38f658c849f93630a/s/master "wercker status")](https://app.wercker.com/project/byKey/518aafc1808a35e38f658c849f93630a) [![StyleCI](https://styleci.io/repos/64166359/shield?style=flat-rounded)](https://styleci.io/repos/64166359)

This is __Rogue__, the DoSomething.org user activity service. Rogue is built using [Laravel 5.4](https://laravel.com/docs/5.4) and [React](http://reactjs.com).

### Getting Started

Check out the [API Documentation](https://github.com/DoSomething/rogue/blob/master/documentation/README.md) to start using
Rogue! :camera_flash:

### Contributing

Fork and clone this repository, and [add it to your Homestead](https://github.com/DoSomething/communal-docs/blob/master/Homestead/readme.md).

```sh
# Install dependencies:
$ composer install && npm install
    
# Copy the default environment variables:
$ cp .env.example .env

# Run database migrations:
$ php artisan migrate

# And finally, build the frontend assets:
$ npm run build
```

You can seed the database with test data:

    $ php artisan db:seed

You may run unit tests locally using PHPUnit:

    $ vendor/bin/phpunit
    
We follow [Laravel's code style](http://laravel.com/docs/5.4/contributions#coding-style) and automatically
lint all pull requests with [StyleCI](https://styleci.io/repos/64166359). Be sure to configure
[EditorConfig](http://editorconfig.org) to ensure you have proper indentation settings.

Consider [writing a test case](http://laravel.com/docs/5.4/testing) when adding or changing a feature.
Most steps you would take when manually testing your code can be automated, which makes it easier for
yourself & others to review your code and ensures we don't accidentally break something later on!

### Security Vulnerabilities
We take security very seriously. Any vulnerabilities in Rogue should be reported to [security@dosomething.org](mailto:security@dosomething.org),
and will be promptly addressed. Thank you for taking the time to responsibly disclose any issues you find.

### License
&copy;2017 DoSomething.org. Rogue is free software, and may be redistributed under the terms specified
in the [LICENSE](https://github.com/DoSomething/rogue/blob/master/LICENSE) file. The name and logo for
DoSomething.org are trademarks of Do Something, Inc and may not be used without permission.
