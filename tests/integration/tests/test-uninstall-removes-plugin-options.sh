#!/bin/bash

BANNER="Options deleted"

$ACTIVATE_PLUGIN > /dev/null

docker-compose $COMPOSER_ARGS exec wordpress wp option add remove_noreferrer '{"remove_settings_on_uninstall":"1"}' --format=json --allow-root > /dev/null

$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

docker-compose $COMPOSER_ARGS exec wordpress wp option get remove_noreferrer --allow-root > /dev/null

if [ $? -ne 1 ]; then
	echo -e "[${BANNER}]: ${red}Option remove_noreferrer must be deleted${NC}"
	exit 1
fi

echo -e "[${BANNER}]:    ${green}Passed${NC}"

exit 0
