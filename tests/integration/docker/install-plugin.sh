#!/bin/bash

set -euo pipefail

PLUGIN_VERSION=$1

wp plugin install /dist/remove-noreferrer-$PLUGIN_VERSION.zip --allow-root
