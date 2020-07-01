#!/bin/bash

set -euo pipefail

curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar --silent
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
