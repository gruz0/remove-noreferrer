#!/bin/bash

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][Homepage]"

$DEACTIVATE_PLUGIN > /dev/null

if ! curl -XGET $WP_HOST --silent | grep post_link | grep noreferrer > /dev/null; then
	echo -e "${BANNER}: ${red}Noreferrer must be exist${NC}"
	exit 1
fi

$DELETE_OPTIONS > /dev/null

docker-compose $COMPOSER_ARGS exec $TTY wordpress wp option add remove_noreferrer '{"where_should_the_plugin_work":["posts_page"]}' --format=json --allow-root > /dev/null

$ACTIVATE_PLUGIN > /dev/null

if ! $PLUGIN_IS_ACTIVE; then
	echo -e "${BANNER}:        ${red}Plugin is not active${red}"
	exit 1
fi

if curl -XGET $WP_HOST --silent | grep post_link | grep noreferrer > /dev/null; then
	echo -e "${BANNER}: ${red}Noreferrer must not be exist${NC}"
	exit 1
fi

echo -e "${BANNER}:           ${green}Passed${NC}"

exit 0
