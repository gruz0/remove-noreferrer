#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Options Deleted]"

$INSTALL_PLUGIN > /dev/null

if ! $PLUGIN_IS_INSTALLED; then
	echo -e "${BANNER}:    ${red}Plugin is not installed${red}"
	exit 1
fi

$ACTIVATE_PLUGIN > /dev/null

if ! $PLUGIN_IS_ACTIVE; then
	echo -e "${BANNER}:    ${red}Plugin is not active${red}"
	exit 1
fi

docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option update remove_noreferrer '{"remove_settings_on_uninstall":"1"}' --format=json --allow-root > /dev/null

$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option list --search="remove_noreferrer" --allow-root | grep remove_noreferrer > /dev/null

if [ "$?" != "1" ]; then
	echo -e "${BANNER}:    ${red}Option remove_noreferrer must be deleted${NC}"
	exit 1
fi

echo -e "${BANNER}:    ${green}Passed${NC}"

exit 0
