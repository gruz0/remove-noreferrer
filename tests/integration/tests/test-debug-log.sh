#!/bin/bash

set -euo pipefail

export red='\033[0;31m'
export green='\033[0;32m'
export NC='\033[0m'

BANNER="[${WP_VERSION}][debug.log]"
DEBUG_LOG="debug.log"

curl -O $WP_HOST/wp-content/debug.log --silent > /dev/null

FILESIZE=$(wc -c < "$DEBUG_LOG")

if [ $FILESIZE -ne 0 ]; then
	echo -e "${BANNER}: ${red}Must be empty${NC}"
	echo
	echo "Content of $DEBUG_LOG:"
	cat $DEBUG_LOG

	rm $DEBUG_LOG

	exit 1
fi

echo -e "${BANNER}:          ${green}Passed${NC}"

rm $DEBUG_LOG

exit 0
