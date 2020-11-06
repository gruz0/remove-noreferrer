#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Without Options]"

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

docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option update remove_noreferrer '{}' --format=json --allow-root > /dev/null

if curl -XGET $WP_HOST --silent | grep "Warning" > /dev/null; then
	echo -e "${BANNER}:    ${red}Some warnings found${NC}"
	exit 1
fi

echo -e "${BANNER}:    ${green}Passed${NC}"

#
# CLEANUP
#
$DELETE_OPTIONS > /dev/null
$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

exit 0
