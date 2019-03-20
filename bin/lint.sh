#!/bin/bash
set -eu

php phpcs/bin/phpcs --colors --standard=phpcs.xml -s .
