This plugin is an open source project and we would love you to help us make it better.

## Reporting Issues

A well formatted issue is appreciated, and goes a long way in helping us help you.

* Make sure you have a [GitHub account](https://github.com/signup/free)
* Submit a [Github issue](https://github.com/gruz0/remove-noreferrer/issues/new) by:
  * Clearly describing the issue
  * Provide a descriptive summary
  * Explain the expected behavior
  * Explain the actual behavior
  * Provide steps to reproduce the actual behavior
  * Put application stacktrace as text (in a [Gist](https://gist.github.com) for bonus points)
  * Any relevant stack traces

If you provide code, make sure it is formatted with the triple backticks (\`\`\`).

At this point, we'd love to tell you how long it will take for us to respond,
but we just don't know.

## Pull requests

We accept pull requests to plugin for:

* Fixing bugs
* Adding new features

Not all features proposed will be added but we are open to having a conversation
about a feature you are championing.

Here's a quick guide:

1. Fork the repo.
2. Create a new branch and make your changes.
3. Push to your fork and submit a pull request. For more information, see
[Github's pull request help section](https://help.github.com/articles/using-pull-requests/).

At this point you're waiting on us.

Expect a conversation regarding your pull request, questions, clarifications, and so on.

## How to run plugin inside Docker environment

Ensure that you have installed Docker and docker-compose in your Operating System.

Then use following commands:

1. `make dockerize` – to run WordPress instance on [http://localhost:8000/](http://localhost:8000/)
2. `make shell` – to open `bash` inside Docker container

After that you can enable Debug mode by running following command
inside Docker's shell:

```bash
./wp-content/plugins/remove-noreferrer/bin/activate_debug.sh
```

It sets `WP_DEBUG`, `WP_DEBUG_DISPLAY` and `WP_DEBUG_LOG` constants to `true`
in `wp-config.php` and sets right permissions to `/var/www/html` directory.

## How to cleanup development database

Simply delete the `.data` directory from the root directory.

## How to install linters locally

Run `make install_linters` inside repo's directory.

What it does:

1. Install [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to `phpcs` directory
2. Install [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) to `rulesets/wpcs` directory
3. Install [WordPress Coding Standards](https://github.com/PHPCompatibility/PHPCompatibility) to `rulesets/PHPCompatibility` directory
4. Install all required composer packages to `vendor` directory
5. Install `bin/pre-commit` Git hook to `.git/hooks/pre-commit`

On each `git commit` commands `make lint` and `make test` will started automatically! :-)

Report's example output:

```bash
$ git commit
[PHP Style][Info]: Checking PHP Style

FILE: .../remove-noreferrer.php
----------------------------------------------------------------------
FOUND 1 ERROR AFFECTING 1 LINE
----------------------------------------------------------------------
 3 | ERROR | You must use "/**" style comments for a file comment
   |       | (Squiz.Commenting.FileComment.WrongStyle)
----------------------------------------------------------------------

Time: 165ms; Memory: 8Mb

[PHP Style][Error]: Fix the issues and commit again
```

## How to run tests

The first one what you need is start testing database:

```bash
make dockerize_test_database
```

It will download latest MariaDB Docker image and start it on `127.0.0.1:3307`.

Then open another terminal window and execute in the plugin directory:

```bash
make install_wordpress_dev
```

It will download latest WordPress version to `tests/wordpress-dev`, then it will
update settings in `tests/wordpress-dev/trunk/wp-tests-config.php`.

If you want to install specific WP version, then pass it as an argument to
`make install_wordpress_dev WP_VERSION=5.3.0`, for example.

And then run tests:

```bash
make test
```
