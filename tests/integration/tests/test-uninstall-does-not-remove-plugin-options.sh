#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Options Saved]"

$INSTALL_PLUGIN > /dev/null

if ! $PLUGIN_IS_INSTALLED; then
	echo -e "${BANNER}:      ${red}Plugin is not installed${red}"
	exit 1
fi

$ACTIVATE_PLUGIN > /dev/null

if ! $PLUGIN_IS_ACTIVE; then
	echo -e "${BANNER}:      ${red}Plugin is not active${red}"
	exit 1
fi

docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option update remove_noreferrer '{"remove_settings_on_uninstall":"0"}' --format=json --allow-root > /dev/null

$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

if ! docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option get remove_noreferrer --allow-root > /dev/null; then
	echo -e "${BANNER}:      ${red}Option remove_noreferrer must not be deleted${NC}"
	exit 1
fi

echo -e "${BANNER}:      ${green}Passed${NC}"

#
# CLEANUP
#
$DELETE_OPTIONS > /dev/null

exit 0
