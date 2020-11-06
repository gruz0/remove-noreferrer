#!/bin/bash

TTY=""

if [ "${IS_GITHUB_ACTIONS}" == "1" ]; then
	TTY=" -T"
fi

export TTY

export COMPOSER_ARGS="-f $BIN/../docker-compose/database.yml -f $BIN/../docker-compose/wordpress-$WP_VERSION.yml"
export WP_HOST="localhost:8000"

export INSTALL_PLUGIN="docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/install-plugin.sh $PLUGIN_VERSION"
export PLUGIN_IS_INSTALLED="docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/check-plugin-is-installed.sh"
export UNINSTALL_PLUGIN="docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/uninstall-plugin.sh"

export ACTIVATE_PLUGIN="docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/activate-plugin.sh"
export PLUGIN_IS_ACTIVE="docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/check-plugin-is-active.sh"
export DEACTIVATE_PLUGIN="docker-compose $COMPOSER_ARGS exec $TTY wordpress /docker/deactivate-plugin.sh"

export DELETE_OPTIONS="docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option delete remove_noreferrer --allow-root"
