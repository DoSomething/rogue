# Rogue [![wercker status](https://app.wercker.com/status/518aafc1808a35e38f658c849f93630a/s/master "wercker status")](https://app.wercker.com/project/byKey/518aafc1808a35e38f658c849f93630a) [![StyleCI](https://styleci.io/repos/64166359/shield?style=flat-rounded)](https://styleci.io/repos/64166359)

This is **Rogue**, the DoSomething.org user activity service. Rogue is built using [Laravel 5.5](https://laravel.com/docs/5.5) and [React](http://reactjs.com).

#### Maintained by
[Jen](https://github.com/ngjo) - Product Manager

[Shae](https://github.com/sbsmith86) - Tech Lead

[Katie](https://github.com/katiecrane) - Engineer

[Chloe](https://github.com/chloealee) - Engineer

[Luke](https://github.com/lkpttn) - Product Designer

[Dave](https://github.com/DFurnes) - Staff Engineer

If you ever have any questions about working in Rogue, please reach out to the #team-bleed slack channel.

### Getting Started

To get started with development, follow the [installation](./docs/development/installation.md) and [contributing](./docs/development/installation.md) documentation.

To get started using the API, see our [API Documentation]()

### Testing

Performance & debug information is available at [`/__clockwork`](http://rogue.test/__clockwork), or using the [Chrome Extension](https://chrome.google.com/webstore/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp).

You can seed the database with test data for local development:

    $ php artisan db:seed

You may run unit tests locally using [PHPUnit](https://laravel.com/docs/5.5/http-tests) & [Jest](https://facebook.github.io/jest/):

    $ phpunit
    $ npm test

And our functional test suite can be run with [Laravel Dusk](https://laravel.com/docs/5.5/dusk):

    $ cp .env.dusk.local.example .env.dusk.local # and add any missing values!
    $ dusk

Consider [writing a test case](http://laravel.com/docs/5.5/testing) when adding or changing a feature.
Most steps you would take when manually testing your code can be automated, which makes it easier for
yourself & others to review your code and ensures we don't accidentally break something later on!

### Security Vulnerabilities

We take security very seriously. Any vulnerabilities in Rogue should be reported to [security@dosomething.org](mailto:security@dosomething.org),
and will be promptly addressed. Thank you for taking the time to responsibly disclose any issues you find.

### License

&copy;2017 DoSomething.org. Rogue is free software, and may be redistributed under the terms specified
in the [LICENSE](https://github.com/DoSomething/rogue/blob/master/LICENSE) file. The name and logo for
DoSomething.org are trademarks of Do Something, Inc and may not be used without permission.
