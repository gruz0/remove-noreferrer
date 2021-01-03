#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Upgrade from 1.2.0]"

$INSTALL_PLUGIN_1_2_0_FROM_WORDPRESS_REPO > /dev/null

if ! $PLUGIN_IS_INSTALLED; then
	echo -e "${BANNER}: ${red}Plugin 1.2.0 is not installed${red}"
	exit 1
fi

$ACTIVATE_PLUGIN > /dev/null

if ! $PLUGIN_IS_ACTIVE; then
	echo -e "${BANNER}: ${red}Plugin 1.2.0 is not active${red}"
	exit 1
fi

$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

# Install new version of the plugin
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

echo -e "${BANNER}: ${green}Passed${NC}"

#
# CLEANUP
#
$DELETE_OPTIONS > /dev/null
$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

exit 0
