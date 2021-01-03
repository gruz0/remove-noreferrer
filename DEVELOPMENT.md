# Development

## Requirements

* PHP 7.1 or higher with install xdebug
* Subversion client on your operating system
* Composer

## How to start

The first one what you need is to run WordPress inside Docker container on
your computer.

Open terminal session and execute:

```sh
make dockerize
```

It will start WordPress on [http://localhost:8000/](http://localhost:8000/).

**After that you need to populate database with some predefined data.**

Simply run:

```sh
make seed
```

What it will do:

* Install WordPress
* Create default administrator
* Create a post
* Create a page
* Create widgets
* Activate Debug Mode

**Now you can log in into [WordPress Dashboard](http://localhost:8000/wp-admin/)
with the following credentials:**

* Login: `admin`
* Password: `password`

And the last step: simply activate `Remove Noreferrer` plugin in the "Plugins"
section in admin area.

## Tests

This project has 2 different types of tests:

* Unit tests (via PHPUnit)
* Integration tests (via Bash)

### Unit tests

You can find unit tests in `./tests/phpunit/tests`.

**Run database inside Docker:**

```sh
make dockerize_test_database
```

**Download and extract required WordPress sources:**

```sh
make install_wordpress_dev
```

By default it uses `latest` WordPress version. If you want to run tests on
specific WordPress version, then pass it as an environment variable:

```sh
make install_wordpress_dev WP_VERSION=5.3
```

**Run PHPUnit:**

```sh
make test
```

If you want to look at code coverage report, then simply run:

```sh
make coverage
```

**Shutdown database:**

```sh
make shutdown_test_database
```

### Integration tests

You can find integration tests in `./tests/integration/tests`.

Run e2e tests on all versions specified in `./tests/integration/docker-compose`:

```sh
make e2e
```

Run e2e tests on specific WordPress version:

```sh
make e2e_single WP_VERSION=5.3
```

## Implementing a new feature or fixing a bug

The first one install required dependencies and Git's `pre-commit` hook locally:

```sh
make setup
```

It will install Composer's dependencies and after that copy `./bin/pre-commit`
file to `./git/hooks/pre-commit`.

Every execution of `git commit` expects that you have running test database.<br />
Simply run it in background: `make dockerize_test_database`.

Now you are ready to implement a new feature or fix a bug.

When you run `git commit` it will do:

* `make lint`
* `make test`

## FAQ

### How to remove development database and containers

```sh
make clean
```

### Where are my logs?

If you want to read debug or error logs, then simply run:

```sh
make shell
```

And then run `tail -f wp-content/debug.log` or access it via browser:
[http://localhost:8000/wp-content/debug.log](http://localhost:8000/wp-content/debug.log).
