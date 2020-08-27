---
title: Overview
sidebar_label: Overview
---

## Architecture

This is **Rogue**, the DoSomething.org user activity service. Rogue is built using [Laravel 6](https://laravel.com/docs/6.x) for the backend on top of a MySQL (Maria DB) database and with a [React](http://reactjs.com) frontend.

## Getting Started

To get started with development, check out our communal docs for installing [Homestead](https://github.com/DoSomething/communal-docs/tree/master/Homestead).

After installation, run `php artisan db:seed` to create sample campaign and activity data.

You can additionally seed your database with sample voter registration data by running:

```php
php artisan db:seed --class=VoterRegistrationSeeder
```

## Security Vulnerabilities

We take security very seriously. Any vulnerabilities in Rogue should be reported to [security@dosomething.org](mailto:security@dosomething.org), and will be promptly addressed. Thank you for taking the time to responsibly disclose any issues you find.

## License

&copy;2020 DoSomething.org. Rogue is free software, and may be redistributed under the terms specified in the [LICENSE](https://github.com/DoSomething/rogue/blob/master/LICENSE) file. The name and logo for DoSomething.org are trademarks of Do Something, Inc and may not be used without permission.
