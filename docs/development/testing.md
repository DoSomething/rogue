### Testing

Consider [writing a test case](http://laravel.com/docs/5.5/testing) when adding or changing a feature in Rogue. Most steps you would take when manually testing your code can be automated, which makes it easier for yourself & others to review your code and ensures we don't accidentally break something later on!

We have a test suite covering core interface and API functionality that should be run against any new feature you are adding to the repository. See below for details.

You may run unit tests locally using [PHPUnit](https://laravel.com/docs/5.5/http-tests) & [Jest](https://facebook.github.io/jest/):

    $ phpunit
    $ npm test

And our functional test suite can be run with [Laravel Dusk](https://laravel.com/docs/5.5/dusk):

    $ cp .env.dusk.local.example .env.dusk.local # and add any missing values!
    $ dusk

### Performance testing

Performance & debug information is available at [`/__clockwork`](http://rogue.test/__clockwork), or using the [Chrome Extension](https://chrome.google.com/webstore/detail/clockwork/dmggabnehkmmfmdffgajcflpdjlnoemp).
