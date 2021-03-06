#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Widget Custom HTML]"

$INSTALL_PLUGIN > /dev/null

if ! $PLUGIN_IS_INSTALLED; then
	echo -e "${BANNER}: ${red}Plugin is not installed${red}"
	exit 1
fi

if ! curl -XGET $WP_HOST --silent | grep widget_custom_html_link | grep noreferrer > /dev/null; then
	echo -e "${BANNER}: ${red}Noreferrer must be exist${NC}"
	exit 1
fi

$ACTIVATE_PLUGIN > /dev/null

if ! $PLUGIN_IS_ACTIVE; then
	echo -e "${BANNER}: ${red}Plugin is not active${red}"
	exit 1
fi

docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option update remove_noreferrer '{"where_should_the_plugin_work":["custom_html_widget"]}' --format=json --allow-root > /dev/null

if curl -XGET $WP_HOST --silent | grep widget_custom_html_link | grep noreferrer > /dev/null; then
	echo -e "${BANNER}: ${red}Noreferrer must not be exist${NC}"
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
