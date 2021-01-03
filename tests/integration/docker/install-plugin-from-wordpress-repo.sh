#!/bin/bash

set -euo pipefail

PLUGIN_VERSION=$1

wp plugin install remove-noreferrer --version=$PLUGIN_VERSION --allow-root
