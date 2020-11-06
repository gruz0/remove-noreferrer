<h1 align="center">Welcome to remove-noreferrer 👋</h1>
<p>
  <img alt="Version" src="https://img.shields.io/badge/version-2.0.0-blue.svg?cacheSeconds=2592000" />
  <a href="https://github.com/gruz0/remove-noreferrer" target="_blank">
    <img alt="Documentation" src="https://img.shields.io/badge/documentation-yes-brightgreen.svg" />
  </a>
  <a href="http://www.gnu.org/licenses/gpl-2.0.txt" target="_blank">
    <img alt="License: GPLv2" src="https://img.shields.io/badge/License-GPLv2-yellow.svg" />
  </a>
</p>

> This WordPress plugin removes rel=&#34;noreferrer&#34; from links in Posts, Pages, comments and widgets

### 🏠 [Homepage](https://wordpress.org/plugins/remove-noreferrer/)

## Run tests

### Unit tests

Run database inside Docker:

```sh
make dockerize_test_database
```

Fetch and extract WordPress sources:

```sh
make install_wordpress_dev
```

By default it uses `latest` WordPress version. If you want run tests on
specific WordPress version, then pass it as an environment variable:

```sh
make install_wordpress_dev WP_VERSION=5.3
```

Run PHPUnit:

```sh
make test
```

Shutdown database:

```sh
make shutdown_test_database
```

### End-to-End tests

Run e2e tests on all version specified in `./tests/integration/docker-compose`:

```sh
make e2e
```

Run e2e tests on specific WordPress version:

```sh
make e2e_single WP_VERSION=5.3
```

## Author

👤 **Alexander Kadyrov**

* Website: https://kadyrov.dev/
* GitHub: [@gruz0](https://github.com/gruz0)
* LinkedIn: [@alexanderkadyrov](https://linkedin.com/in/alexanderkadyrov)

## 🤝 Contributing

Contributions, issues and feature requests are welcome!<br />Feel free to check [issues page](https://github.com/gruz0/remove-noreferrer/issues). You can also take a look at the [contributing guide](https://github.com/gruz0/remove-noreferrer/blob/master/CONTRIBUTING.md).

## Show your support

Give a ⭐️ if this project helped you!

<a href="https://www.patreon.com/kadyrov">
  <img src="https://c5.patreon.com/external/logo/become_a_patron_button@2x.png" width="160">
</a>

## 📝 License

Copyright © 2020 [Alexander Kadyrov](https://github.com/gruz0).<br />
This project is [GPLv2](http://www.gnu.org/licenses/gpl-2.0.txt) licensed.

***
_This README was generated with ❤️ by [readme-md-generator](https://github.com/kefranabg/readme-md-generator)_
