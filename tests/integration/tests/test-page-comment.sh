#!/bin/bash

BANNER="Page Comment"

PAGE_ID=$(docker-compose $COMPOSER_ARGS exec wordpress wp post list --allow-root --post_type='page' --format=ids)

URL="$WP_HOST/?page_id=$PAGE_ID"

$DEACTIVATE_PLUGIN > /dev/null

curl -XGET $URL --silent | grep page_comment_link | grep noreferrer > /dev/null

if [ $? -ne 0 ]; then
	echo -e "[${BANNER}]: ${red}Noreferrer must be exist${NC}"
	exit 1
fi

$DELETE_OPTIONS > /dev/null

docker-compose $COMPOSER_ARGS exec wordpress wp option add remove_noreferrer '{"where_should_the_plugin_work":["comments"]}' --format=json --allow-root > /dev/null

$ACTIVATE_PLUGIN > /dev/null

curl -XGET $URL --silent | grep page_comment_link | grep noreferrer > /dev/null

if [ $? -ne 1 ]; then
	echo -e "[${BANNER}]: ${red}Noreferrer must not be exist${NC}"
	exit 1
fi

echo -e "[${BANNER}]:       ${green}Passed${NC}"

exit 0
