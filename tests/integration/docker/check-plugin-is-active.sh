#!/bin/bash

set -euo pipefail

wp plugin is-active remove-noreferrer --user=1 --allow-root
