#!/bin/bash

set -euo pipefail

touch wp-content/debug.log
chown www-data:www-data wp-content/debug.log
