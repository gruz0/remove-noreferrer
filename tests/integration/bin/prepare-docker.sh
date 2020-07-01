#!/bin/bash

set -euo pipefail

docker-compose $COMPOSER_ARGS exec wordpress /docker/install-wp-cli.sh
docker-compose $COMPOSER_ARGS exec wordpress /docker/activate-debug.sh
docker-compose $COMPOSER_ARGS exec wordpress /docker/fix-permissions.sh
docker-compose $COMPOSER_ARGS exec wordpress /docker/prepare.sh $WP_HOST
docker-compose $COMPOSER_ARGS exec wordpress /docker/install-plugin.sh
docker-compose $COMPOSER_ARGS exec wordpress /docker/touch-debug-log.sh
