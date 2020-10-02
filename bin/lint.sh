#!/bin/bash
set -euo pipefail

./vendor/bin/phpcs --colors --standard=phpcs.xml -s .
