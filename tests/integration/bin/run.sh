#!/bin/bash

set -euo pipefail

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BIN=$(dirname $0)
export BIN

WP_VERSION=$1
export PLUGIN_VERSION=$2

. $BIN/variables.sh

echo -e "${green}Run tests on WordPress ${WP_VERSION}${NC}"

$BIN/start-wordpress.sh
$BIN/prepare-docker.sh
$BIN/tests.sh

docker-compose $COMPOSER_ARGS down > /dev/null
