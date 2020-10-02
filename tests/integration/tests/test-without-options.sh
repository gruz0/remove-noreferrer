#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Without Options]"

$DEACTIVATE_PLUGIN > /dev/null

$ACTIVATE_PLUGIN > /dev/null

if ! $PLUGIN_IS_ACTIVE; then
	echo -e "${BANNER}:        ${red}Plugin is not active${red}"
	exit 1
fi

$DELETE_OPTIONS > /dev/null

if curl -XGET $WP_HOST --silent | grep "Warning" > /dev/null; then
	echo -e "${BANNER}: ${red}Some warnings found${NC}"
	exit 1
fi

echo -e "${BANNER}:    ${green}Passed${NC}"

exit 0
