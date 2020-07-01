#!/bin/bash

BANNER="Post Comment"

POST_ID=$(docker-compose $COMPOSER_ARGS exec wordpress wp post list --allow-root --post_type='post' --format=ids)

URL="$WP_HOST/?p=$POST_ID"

$DEACTIVATE_PLUGIN > /dev/null

curl -XGET $URL --silent | grep post_comment_link | grep noreferrer > /dev/null

if [ $? -ne 0 ]; then
	echo -e "[${BANNER}]: ${red}Noreferrer must be exist${NC}"
	exit 1
fi

$ACTIVATE_PLUGIN > /dev/null

curl -XGET $URL --silent | grep post_comment_link | grep noreferrer > /dev/null

if [ $? -ne 1 ]; then
	echo -e "[${BANNER}]: ${red}Noreferrer must not be exist${NC}"
	exit 1
fi

echo -e "[${BANNER}]:       ${green}Passed${NC}"

exit 0
