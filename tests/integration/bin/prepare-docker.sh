#!/bin/bash

set -euo pipefail

docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/install-wp-cli.sh
docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/activate-debug.sh
docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/fix-permissions.sh
docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/prepare.sh $WP_HOST
docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/touch-debug-log.sh
