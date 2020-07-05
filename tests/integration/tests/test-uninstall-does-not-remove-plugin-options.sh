#!/bin/bash

BANNER="Options saved"

$ACTIVATE_PLUGIN > /dev/null

$DELETE_OPTIONS > /dev/null

docker-compose $COMPOSER_ARGS exec wordpress wp option add remove_noreferrer '{"remove_settings_on_uninstall":"0"}' --format=json --allow-root > /dev/null

$DEACTIVATE_PLUGIN > /dev/null
$UNINSTALL_PLUGIN > /dev/null

OPTION=$(docker-compose $COMPOSER_ARGS exec wordpress wp option get remove_noreferrer --allow-root)

if [ $? -ne 0 ]; then
	echo -e "[${BANNER}]: ${red}Option remove_noreferrer must not be deleted${NC}"
	exit 1
fi

echo -e "[${BANNER}]:      ${green}Passed${NC}"

exit 0
