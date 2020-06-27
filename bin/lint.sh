#!/bin/bash
set -euo pipefail

php phpcs/bin/phpcs --colors --standard=phpcs.xml -s .
