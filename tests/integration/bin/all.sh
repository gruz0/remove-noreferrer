#!/bin/bash

set -euo pipefail

export BIN=$(dirname $0)

PLUGIN_VERSION=$1

for filename in $BIN/../docker-compose/wordpress-*.yml; do
	file=$(basename $filename)
	version=${file#'wordpress-'}
	version=${version%'.yml'}

	$BIN/run.sh $version $PLUGIN_VERSION
done
