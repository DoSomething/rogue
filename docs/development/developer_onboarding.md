# Developer Onboarding

## Install and Set Up Rogue
You can follow the [instructions](installation.md) to install and set up Rogue on your local development environment.

However, before running `php artisan rogue:setup`, you are going to need a few permissions so that you can set the correct application keys and secrets for proper access.


### Northstar
For the most part, you can use the QA Northstar URL for your development environment: `https://northstar-qa.dosomething.org`

To gain access to an ID and SECRET, you will need to have the proper permissions set on your DoSomething account. Let a developer on the team know if you are unable to access [Aurora (QA)](https://aurora-qa.dosomething.org/clients).


### .ENV (noun: /Dot EE EN VEE/)
The `.env` file provides environment specific configurations that can be set differently depending on the environment (local, qa, production, etc). Here is what an [example.env](https://github.com/DoSomething/rogue/blob/master/.env.example) looks like from the repository.

If running the Rogue setup from scratch, a copy of this `.env.example` file will be made and renamed to `.env`. Make sure to update the new file with the corresponding values you need for your local setup. If you have questions about which values to use, reach out to the developer team.

### Database Seeding

You can seed your local database with test data for local development:

    $ php artisan db:seed

### API Development

For local development of the Rogue API you will need an API tool like [Postman](https://www.getpostman.com/products) or [Paw](https://paw.cloud/). Tools like these will allow you to make API requests.

There are many ways of using these tools and customizing them for efficient use. Reach out to the developer team for any tips or tricks of using one of thse tools.

### Access and Permissions
There are a couple of other applications and sites that you will want access to for development. Please reach out to the developer team if you are having issues with accessing these tools.

- The [Dosomething Team](https://dashboard.heroku.com/teams/dosomething/overview) on [Heroku](https://www.heroku.com/) (To access Rogue Apps, and more).

- The [Dosomething Organization](https://app.wercker.com/dosomething) on [Wercker](https://app.wercker.com) (To access and re-trigger auto builds for _Pull Requests_).
