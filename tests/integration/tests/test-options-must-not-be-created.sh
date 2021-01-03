#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Migrations Skipped]"

$INSTALL_PLUGIN > /dev/null

if ! $PLUGIN_IS_INSTALLED; then
	echo -e "${BANNER}: ${red}Plugin is not installed${red}"
	exit 1
fi

$ACTIVATE_PLUGIN > /dev/null

if ! $PLUGIN_IS_ACTIVE; then
	echo -e "${BANNER}: ${red}Plugin is not active${red}"
	exit 1
fi

# This step checks that the new version of the plugin does not run \Remove_Noreferrer\Admin\Options_Migrator on frontend
if docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option get remove_noreferrer --format=json --allow-root > /dev/null; then
	echo -e "${BANNER}: ${red}Options must not be created${NC}"
	exit 1
fi

echo -e "${BANNER}: ${green}Passed${NC}"

#
# CLEANUP
#
$DELETE_OPTIONS > /dev/null
$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

exit 0
