#!/bin/bash

set -euo pipefail

export BIN=$(dirname $0)
WP_VERSION=$1
export PLUGIN_VERSION=$2

. $BIN/variables.sh

echo -e "${green}Run tests on WordPress ${WP_VERSION}${NC}"

$BIN/start-wordpress.sh
$BIN/prepare-docker.sh
$BIN/tests.sh

docker-compose $COMPOSER_ARGS down > /dev/null
