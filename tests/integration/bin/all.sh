#!/bin/bash

set -euo pipefail

BIN=$(dirname $0)
export BIN

PLUGIN_VERSION=$1

for filename in "$BIN"/../docker-compose/wordpress-*.yml; do
	file=$(basename $filename)
	version=${file#'wordpress-'}
	version=${version%'.yml'}

	$BIN/run.sh $version $PLUGIN_VERSION
done
