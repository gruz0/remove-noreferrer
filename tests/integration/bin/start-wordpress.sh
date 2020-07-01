#!/bin/bash

set -euo pipefail

docker-compose $COMPOSER_ARGS down
docker-compose $COMPOSER_ARGS up --build -d

while ! curl -L $WP_HOST --silent | grep 'Continue' > /dev/null; do
	sleep 1
done

exit 0
