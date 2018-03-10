# Contributing

This section will go over some general points about contributing to Rogue as a developer. If you have any specific questions that are not addressed here, please reach out to Team Bleed.

## Pivotal Tracker
We use [Pivotal Tracker](https://www.pivotaltracker.com/n/projects/2019429) as our project management tool for tracking all of our teams work sprint-by-sprint.

Rogue maintenance and features are maintained primarily by Team Bleed. Please reach out to the product
team if you are having issues accessing the Team Bleed pivotal board.

## GitHub

We use GitHub for our code managment platform. All of our code at DoSomething.org is open source and available to the public on GitHub. To start contrubuting, you only need to create a GitHub account (if you don't already have one) and visit the [Rogue Repository](https://github.com/DoSomething/rogue) which includes installation instructions for local development.

### Workflow

When contributing to Rogue, follow these steps:

1) Pull down the most recent code on the `master` branch.
2) Create a branch off of your local master branch and give it a name.
3) Commit all of your code changes to this new branch.
4) Rebase your feature branch with `master` and push your branch up to the Rogue Repository on GitHub.
5) Create a [Pull Request](https://help.github.com/articles/about-pull-requests/) that compares your branch to `master` and add `DoSomething/team-bleed` as a reviewer.
6) Members of #Team-Bleed will then review your proposed changes in a timely manner. There might be questions, comments, or suggestions that need to be addressed before final approval is recieved.
7) Once everything looks OK your PR will be officially approved and if all the CLI integrations have passed you will be free to merge your code.
8) Click the green `Merge` button and your code will be merged into master. See the [Deployments](/deployments.md) for information about our deployment process.

### Guidelines
We follow [Laravel's code style](http://laravel.com/docs/5.5/contributions#coding-style) and automatically
lint all pull requests with [StyleCI](https://styleci.io/repos/64166359). Be sure to configure
[EditorConfig](http://editorconfig.org) to ensure you have proper indentation settings.
