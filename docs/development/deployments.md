# Deployments

We generally deploy Rogue every weekday morning at 10:30am EST. That being said, we encourage developers to be continously deploying changes to production throughout the day to keep deployments as small and as isolated as possible.

The general rule of thumb is to do a quick check with the developer team in the #rogue slack channel to confirm it is OK to deploy to production. If it is late in the day, a Friday, or there is some other blocking change, we might ask that we hold off on a production deployment.

# General deployment workflow

When you submit a PR against the master branch in the [Rogue repository](https://github.com/DoSomething/rogue) a [Wercker](https://app.wercker.com/dosomething/rogue/runs) build will be triggered and deployed to a Review App on Heroku. You can use this Review app to share and test your specific changes. Once the PR is merged and closed, a deployment will be automatically triggered to the QA and staging environments. See the [Architecture](/docs/development/architecture.md) section for more information on the differences between environments.

Once your code is tested on rogue-staging (a.k.a rogue-thor), you can trigger a deployment to Rogue production.

# How to deploy to production

You can deploy manually on Heroku itself, by going to our [Rogue pipline](https://dashboard.heroku.com/pipelines/289a727e-1b35-401e-8c80-4b0bf6dd4a77) and clicking the "Promote Changes" button on the staging environment. This will deploy the same build onto production. If that build is successfully deployed, you can test your changes on Rogue production and consider your work done. If you see "No changes to promote" instead, then that environmnet has no new changes from the envrionment next in the pipline.

You can also deploy using the Heroku Slack Integration, [ChatOps](https://devcenter.heroku.com/articles/chatops).

# Heroku

Heroku is a platform that lets developers focus on building applications instead of orchestrating infastructure. We currently use Heroku for hosting our different Rogue environments including Review Applications, temporary apps that deploy for every pull request, Rogue-QA, Rogue-staging, and Rogue-production.

## Heroku Resources

Pages useful for understanding and debugging Heroku.
* [12 Factor](https://12factor.net/)
* [Heroku Dev Center](https://devcenter.heroku.com/)
* [Heroku Laravel Buildpack](https://devcenter.heroku.com/articles/getting-started-with-laravel)
* [Heroku Javascript Buildpack](https://devcenter.heroku.com/articles/deploying-nodejs)
* [Heroku Review Apps Documentation](https://devcenter.heroku.com/articles/deploying-nodejs)

## Laravel + Heroku notes

Review apps are spun up everytime a pull request is made by using the [app.json](https://github.com/DoSomething/rogue/blob/master/app.json) as an instruction set for how to configure the dyno. Any secret environment variables that it pulls in come from the staging app within the Rogue Heroku pipeline.
