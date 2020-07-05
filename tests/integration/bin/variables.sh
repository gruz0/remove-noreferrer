#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

export COMPOSER_ARGS="-f $BIN/../docker-compose/database.yml -f $BIN/../docker-compose/wordpress-$WP_VERSION.yml"
export WP_HOST="localhost:8000"

export DEACTIVATE_PLUGIN="docker-compose $COMPOSER_ARGS exec wordpress /docker/deactivate-plugin.sh"
export ACTIVATE_PLUGIN="docker-compose $COMPOSER_ARGS exec wordpress /docker/activate-plugin.sh"
export UNINSTALL_PLUGIN="docker-compose $COMPOSER_ARGS exec wordpress /docker/uninstall-plugin.sh"

export DELETE_OPTIONS="docker-compose $COMPOSER_ARGS exec wordpress wp option delete remove_noreferrer --allow-root"
